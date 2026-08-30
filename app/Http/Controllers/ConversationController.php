<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Service;
use App\Services\NotificationService;
use Illuminate\Broadcasting\BroadcastException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ConversationController extends Controller
{
    public function start(Service $service)
    {
        abort_unless($service->status === 'approved', 404);
        abort_if($service->user_id === auth()->id(), 403, 'Anda tidak dapat menghubungi jasa milik sendiri.');

        $conversation = Conversation::firstOrCreate([
            'service_id' => $service->id,
            'buyer_id' => auth()->id(),
            'seller_id' => $service->user_id,
        ]);

        if ($conversation->wasRecentlyCreated) {
            NotificationService::createAndDispatch(
                userId: $service->user_id,
                type: 'message',
                title: 'Percakapan baru dari ' . auth()->user()->name,
                message: 'Membuka diskusi untuk jasa "' . $service->title . '".',
                extraData: [
                    'service_id' => $service->id,
                    'conversation_id' => $conversation->id,
                ]
            );
        }

        return redirect()->route('conversations.show', $conversation);
    }

    public function index()
    {
        return $this->render('buyer_id');
    }

    public function sellerIndex()
    {
        return $this->render('seller_id');
    }

    private function render(string $participantColumn, ?Conversation $conversation = null)
    {
        $filter = request()->query('filter', 'all'); // all | unread | read
        $conversations = $this->sidebarQuery($participantColumn, $filter)->paginate(15);

        if ($conversation) {
            $this->authorizeParticipant($conversation);
            $conversation->messages()
                ->where('sender_id', '!=', auth()->id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
            $conversation->load(['service.seller', 'buyer', 'seller', 'messages.sender', 'priceOffers']);
        }

        $title = $participantColumn === 'buyer_id' ? 'Percakapan saya' : 'Pesan masuk';
        $mode = $participantColumn === 'buyer_id' ? 'buyer' : 'seller';
        $indexRoute = $mode === 'buyer' ? 'conversations.index' : 'conversations.seller-index';

        return view('conversations.index', compact('conversations', 'conversation', 'filter', 'title', 'mode', 'indexRoute'));
    }

    public function show(Conversation $conversation)
    {
        $participantColumn = $conversation->seller_id === auth()->id() ? 'seller_id' : 'buyer_id';

        return $this->render($participantColumn, $conversation);
    }

    private function sidebarQuery(string $participantColumn, string $filter)
    {
        $userId = auth()->id();

        return Conversation::query()
            ->where($participantColumn, $userId)
            ->with(['service', 'buyer', 'seller', 'latestMessage'])
            ->withCount(['messages as unread_count' => fn ($query) => $query->where('sender_id', '!=', $userId)->whereNull('read_at')])
            ->when($filter === 'unread', fn ($query) => $query->whereHas('messages', fn ($message) => $message->where('sender_id', '!=', $userId)->whereNull('read_at')))
            ->when($filter === 'read', fn ($query) => $query->whereDoesntHave('messages', fn ($message) => $message->where('sender_id', '!=', $userId)->whereNull('read_at')))
            ->latest('updated_at');
    }

    public function store(Request $request, Conversation $conversation)
    {
        $this->authorizeParticipant($conversation);

        $validated = $request->validate(['message' => ['required', 'string', 'max:1500']]);
        $message = $conversation->messages()->create([
            'sender_id' => auth()->id(),
            'message' => trim($validated['message']),
        ])->load('sender');

        $conversation->touch();
        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (BroadcastException $exception) {
            // Pesan sudah aman tersimpan. Kegagalan server WebSocket tidak boleh
            // membuat pengguna mengirim ulang pesan yang sama.
            report($exception);
        }

        // Kirim notifikasi ke penerima (bukan pengirim) agar ia tahu ada pesan baru.
        $recipientId = $conversation->buyer_id === auth()->id() ? $conversation->seller_id : $conversation->buyer_id;
        if ($recipientId) {
            NotificationService::createAndDispatch(
                userId: $recipientId,
                type: 'message',
                title: 'Pesan baru dari ' . auth()->user()->name,
                message: Str::limit($message->message, 100),
                extraData: [
                    'service_id' => $conversation->service_id,
                    'conversation_id' => $conversation->id,
                ]
            );
        }

        return response()->json(['message' => [
            'id' => $message->id,
            'sender_id' => $message->sender_id,
            'sender_name' => $message->sender->name,
            'message' => $message->message,
            'created_at' => $message->created_at->format('H:i'),
        ]], 201);
    }

    private function authorizeParticipant(Conversation $conversation): void
    {
        abort_unless($conversation->hasParticipant(auth()->user()), 403);
    }
}
