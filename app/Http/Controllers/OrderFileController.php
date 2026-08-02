<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// app/Http/Controllers/OrderFileController.php
class OrderFileController extends Controller
{
    public function store(Request $request, Order $order)
    {
        $validated = $request->validate([
            'file' => 'required|file|max:5120', // 5MB
            'file_type' => 'required|in:kebutuhan,hasil,revisi',
        ]);

        $isBuyer = $order->buyer_id === auth()->id();
        $isSeller = $order->service->user_id === auth()->id();

        // Buyer hanya boleh upload 'kebutuhan', seller hanya boleh upload 'hasil'/'revisi'
        if ($validated['file_type'] === 'kebutuhan' && !$isBuyer) {
            abort(403, 'Hanya buyer yang bisa mengunggah file kebutuhan.');
        }
        if (in_array($validated['file_type'], ['hasil', 'revisi']) && !$isSeller) {
            abort(403, 'Hanya seller yang bisa mengunggah hasil kerja.');
        }

        $path = $request->file('file')->store('order-files', 'public');

        $order->files()->create([
            'uploader_id' => auth()->id(),
            'file_type' => $validated['file_type'],
            'file_path' => $path,
        ]);

        // Kalau seller upload hasil, order otomatis masuk status "menunggu persetujuan"
        if (in_array($validated['file_type'], ['hasil', 'revisi'])) {
            $order->update(['status' => 'menunggu_persetujuan']);
        }

        return back()->with('success', 'File berhasil diunggah.');
    }

    /**
     * Buyer menyetujui hasil kerja -> order selesai, siap dicairkan admin.
     */
    public function approve(Order $order)
    {
        if ($order->buyer_id !== auth()->id()) {
            abort(403, 'Hanya buyer yang bisa menyetujui hasil kerja.');
        }

        $order->update(['status' => 'selesai']);

        return back()->with('success', 'Hasil kerja disetujui! Dana akan segera dicairkan admin ke seller.');
    }

    /**
     * Buyer meminta revisi -> order kembali ke status dikerjakan.
     */
    public function requestRevision(Request $request, Order $order)
    {
        if ($order->buyer_id !== auth()->id()) {
            abort(403, 'Hanya buyer yang bisa meminta revisi.');
        }

        $validated = $request->validate([
            'revision_note' => 'required|string|max:500',
        ]);

        $order->update(['status' => 'dikerjakan']);

        // Catatan revisi disimpan sebagai pesan diskusi, supaya seller langsung lihat alasannya
        $order->messages()->create([
            'sender_id' => auth()->id(),
            'message' => '[Minta Revisi] ' . $validated['revision_note'],
        ]);

        return back()->with('success', 'Permintaan revisi terkirim ke seller.');
    }
}