<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NegotiationController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'offered_price' => 'required|numeric|min:1000',
        ]);

        // Tawaran sebelumnya yang masih pending otomatis dianggap kadaluarsa
        $order->negotiations()->where('status', 'pending')->update(['status' => 'rejected']);

        $order->negotiations()->create([
            'sender_id' => auth()->id(),
            'offered_price' => $validated['offered_price'],
            'status' => 'pending',
        ]);

        return back()->with('success', 'Tawaran harga terkirim.');
    }

    public function accept(Negotiation $negotiation)
    {
        $order = $negotiation->order;

        $isParticipant = in_array(auth()->id(), [$order->buyer_id, $order->service->user_id]);
        if (! $isParticipant || $negotiation->sender_id === auth()->id()) {
            abort(403, 'Anda tidak berhak menyetujui tawaran ini.');
        }

        if ($negotiation->status !== 'pending') {
            return back()->with('error', 'Tawaran ini sudah diproses sebelumnya.');
        }

        $negotiation->update(['status' => 'accepted']);
        $order->update(['final_price' => $negotiation->offered_price]);

        return back()->with('success', 'Harga baru disepakati: Rp' . number_format($negotiation->offered_price, 0, ',', '.'));
    }
}
