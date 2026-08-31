<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function create(Service $service)
    {
        abort_unless($service->status === 'approved', 404, 'Jasa tidak tersedia.');

        return view('orders.create', compact('service'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'message' => 'nullable|string|max:1000',
        ]);

        $service = Service::approved()->findOrFail($validated['service_id']);

        if ($service->user_id === auth()->id()) {
            abort(403, 'Anda tidak bisa memesan jasa milik sendiri.');
        }

        $order = null;

        if (! empty($validated['message'])) {
            $order = Order::query()
                ->where('service_id', $service->id)
                ->where('buyer_id', auth()->id())
                ->whereNotIn('status', ['selesai'])
                ->latest()
                ->first();
        }

        $order ??= Order::create([
            'service_id' => $service->id,
            'buyer_id' => auth()->id(),
            'status' => 'menunggu_pembayaran',
            'final_price' => $service->price,
        ]);

        if (! empty($validated['message'])) {
            $order->messages()->create([
                'sender_id' => auth()->id(),
                'message' => $validated['message'],
            ]);
        }

        return redirect()->route('orders.show', $order)
            ->with('success', ! empty($validated['message'])
                ? 'Pesanmu sudah dikirim ke penyedia jasa.'
                : 'Pesanan dibuat. Anda bisa negosiasi harga atau langsung bayar.');
    }

    public function show(Order $order)
    {
        // Hanya buyer, seller (pemilik jasa), atau admin yang boleh lihat
        $isBuyer = $order->buyer_id === auth()->id();
        $isSeller = $order->service->user_id === auth()->id();
        if (!$isBuyer && !$isSeller && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $order->load(['service.seller', 'buyer', 'negotiations.sender', 'messages.sender', 'files', 'payment']);

        return view('orders.show', compact('order', 'isBuyer', 'isSeller'));
    }

    public function index(Request $request)
    {
        $userId = auth()->id();
        $statusFilter = $request->query('status', 'all');
        $role = $request->query('role', 'all'); // all | buyer | seller

        $roleScope = function ($query) use ($userId, $role) {
            if ($role === 'buyer') {
                $query->where('buyer_id', $userId);
            } elseif ($role === 'seller') {
                $query->whereHas('service', fn ($service) => $service->where('user_id', $userId));
            } else {
                $query->where('buyer_id', $userId)
                      ->orWhereHas('service', fn ($service) => $service->where('user_id', $userId));
            }
        };

        $statusMap = [
            'pending' => ['menunggu_pembayaran', 'menunggu_verifikasi'],
            'processing' => ['dibayar', 'dikerjakan'],
            'in_progress' => ['dibayar', 'dikerjakan', 'menunggu_persetujuan'],
            'completed' => ['selesai'],
            'cancelled' => ['dibatalkan'],
        ];

        $totalOrders = Order::where($roleScope)->count();
        $completedCount = Order::where($roleScope)->whereIn('status', $statusMap['completed'])->count();
        $inProgressCount = Order::where($roleScope)->whereIn('status', $statusMap['in_progress'])->count();
        $pendingCount = Order::where($roleScope)->whereIn('status', $statusMap['pending'])->count();

        $orders = Order::where($roleScope)
            ->with(['service.seller', 'buyer'])
            ->when($statusFilter !== 'all' && isset($statusMap[$statusFilter]), fn ($query) => $query->whereIn('status', $statusMap[$statusFilter]))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Pre-determine seller status for orders to avoid N+1 queries
        $orders->getCollection()->transform(function ($order) {
            $order->is_seller = $order->service->user_id === auth()->id();
            return $order;
        });

        return view('orders.index', compact(
            'orders',
            'totalOrders',
            'completedCount',
            'inProgressCount',
            'pendingCount',
            'statusFilter',
            'role'
        ));
    }

    public function conversation(Order $order)
    {
        $isBuyer = $order->buyer_id === auth()->id();
        $isSeller = $order->service->user_id === auth()->id();
        
        if (!$isBuyer && !$isSeller && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $conversation = \App\Models\Conversation::firstOrCreate([
            'service_id' => $order->service_id,
            'buyer_id' => $order->buyer_id,
            'seller_id' => $order->service->user_id,
        ]);

        return redirect()->route('conversations.show', $conversation);
    }
}
