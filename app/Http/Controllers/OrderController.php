<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;

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

    public function index()
    {
        $orders = Order::where('buyer_id', auth()->id())
            ->orWhereHas('service', fn ($q) => $q->where('user_id', auth()->id()))
            ->with(['service.seller', 'buyer'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }
}
