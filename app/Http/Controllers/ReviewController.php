<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Order $order)
{
    if ($order->buyer_id !== auth()->id()) {
        abort(403, 'Hanya buyer yang bisa memberi review.');
    }

    if ($order->status !== 'selesai') {
        return back()->with('error', 'Review hanya bisa diberikan setelah pesanan selesai.');
    }

    if ($order->review) {
        return back()->with('error', 'Anda sudah memberi review untuk pesanan ini.');
    }

    $validated = $request->validate([
        'rating' => 'required|integer|min:1|max:5',
        'comment' => 'nullable|string|max:500',
    ]);

    $order->review()->create($validated);

    return back()->with('success', 'Terima kasih atas review Anda!');
}

}
