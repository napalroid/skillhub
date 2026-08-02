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

    public function accept(Order $order, Negotiation $negotiation)
    {
        // Hanya lawan bicara (bukan pengirim tawaran) yang boleh menerima
        if ($negotiation->sender_id === auth()->id()) {
            abort(403, 'Anda tidak bisa menyetujui tawaran sendiri.');
        }

        $negotiation->update(['status' => 'accepted']);
        $order->update(['final_price' => $negotiation->offered_price]);

        return back()->with('success', 'Harga baru disepakati: Rp' . number_format($negotiation->offered_price, 0, ',', '.'));
    }
}
