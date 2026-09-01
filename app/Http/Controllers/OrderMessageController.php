<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderMessageController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $order->messages()->create([
            'sender_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        return back()->with('success', 'Pesan terkirim.');
    }
}
