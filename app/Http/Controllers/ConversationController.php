<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Service;
use App\Models\UserNotification;
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
            UserNotification::create([
                'user_id' => $service->user_id,
                'service_id' => $service->id,
                'conversation_id' => $conversation->id,
                'type' => 'message',
                'title' => 'Percakapan baru dari ' . auth()->user()->name,
                'message' => 'Membuka diskusi untuk jasa "' . $service->title . '".',
                'is_read' => false,
            ]);
        }

        return redirect()->route('conversations.show', $conversation);
    }

    public function index()
    {
        return $this->list('buyer_id', 'Percakapan saya');
    }

    public function sellerIndex()
    {
        return $this->list('seller_id', 'Pesan masuk');
    }

    private function list(string $participantColumn, string $title)
    {
        $userId = auth()->id();
        $conversations = Conversation::query()
            ->where($participantColumn, $userId)
            ->with(['service', 'buyer', 'seller', 'latestMessage'])
            ->withCount(['messages as unread_count' => fn ($query) => $query->where('sender_id', '!=', $userId)->whereNull('read_at')])
            ->latest('updated_at')
            ->paginate(15);

        return view('conversations.index', compact('conversations', 'title'));
    }

    public function show(Conversation $conversation)
    {
        $this->authorizeParticipant($conversation);

        $conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $conversation->load(['service.seller', 'buyer', 'seller', 'messages.sender', 'priceOffers']);

        return view('conversations.show', compact('conversation'));
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
            UserNotification::create([
                'user_id' => $recipientId,
                'service_id' => $conversation->service_id,
                'conversation_id' => $conversation->id,
                'type' => 'message',
                'title' => 'Pesan baru dari ' . auth()->user()->name,
                'message' => Str::limit($message->message, 100),
                'is_read' => false,
            ]);
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
