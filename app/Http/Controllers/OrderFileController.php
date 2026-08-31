<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\NotificationService;
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
        if ($validated['file_type'] === 'kebutuhan' && ! $isBuyer) {
            abort(403, 'Hanya buyer yang bisa mengunggah file kebutuhan.');
        }
        if (in_array($validated['file_type'], ['hasil', 'revisi']) && ! $isSeller) {
            abort(403, 'Hanya seller yang bisa mengunggah hasil kerja.');
        }

        // C2: seller hanya boleh upload hasil/revisi kalau dana sudah di-escrow (payment verified)
        if (in_array($validated['file_type'], ['hasil', 'revisi'])) {
            if (! $order->canBeDelivered()) {
                abort(403, 'Pesanan belum bisa dikirimi hasil (status tidak sesuai).');
            }
            if (! $order->payment || $order->payment->status !== 'verified') {
                abort(403, 'Dana escrow belum ditahan. Tunggu admin konfirmasi saldo sebelum mengirim hasil.');
            }
        }

        $path = $request->file('file')->store('order-files', 'public');

        $order->files()->create([
            'uploader_id' => auth()->id(),
            'file_type' => $validated['file_type'],
            'file_path' => $path,
        ]);

        // Kalau seller upload hasil, order masuk status "menunggu persetujuan"
        // (hanya kalau belum selesai/dibatalkan)
        if (in_array($validated['file_type'], ['hasil', 'revisi'])
            && in_array($order->status, [
                Order::STATUS_DIBAYAR,
                Order::STATUS_DIKERJAKAN,
                Order::STATUS_MENUNGGU_PERSETUJUAN,
            ], true)) {
            $order->update(['status' => Order::STATUS_MENUNGGU_PERSETUJUAN]);
        }

        return back()->with('success', 'File berhasil diunggah.');
    }

    /**
     * Seller menandai pesanan mulai dikerjakan.
     */
    public function startWork(Order $order)
    {
        if ($order->service->user_id !== auth()->id()) {
            abort(403, 'Hanya seller yang bisa memulai pengerjaan.');
        }

        if (! $order->canBeStartedBySeller()) {
            return back()->with('error', 'Pesanan belum bisa dikerjakan (status tidak sesuai).');
        }

        if (! $order->payment || $order->payment->status !== 'verified') {
            return back()->with('error', 'Dana escrow belum ditahan. Tunggu admin konfirmasi saldo.');
        }

        $order->update(['status' => Order::STATUS_DIKERJAKAN]);

        return back()->with('success', 'Pengerjaan pesanan telah dimulai.');
    }

    /**
     * Buyer menyetujui hasil kerja -> order selesai, memicu pencairan otomatis (1 jam).
     * C1 + C5: hanya dari status 'menunggu_persetujuan' & idempoten.
     */
    public function approve(Order $order)
    {
        if ($order->buyer_id !== auth()->id()) {
            abort(403, 'Hanya buyer yang bisa menyetujui hasil kerja.');
        }

        if ($order->status === Order::STATUS_SELESAI) {
            return back()->with('info', 'Pesanan ini sudah selesai.');
        }

        if (!$order->canBeCompletedByBuyer()) {
            return back()->with('error', 'Pesanan belum bisa diselesaikan. Status saat ini: ' . $order->status);
        }

        if (!$order->files()->where('file_type', 'hasil')->exists()) {
            return back()->with('error', 'Hasil kerja belum diunggah oleh seller.');
        }

        $order->update([
            'status' => Order::STATUS_SELESAI,
            'completed_at' => now(),
        ]);

        NotificationService::createAndDispatch(
            userId: $order->service->user_id,
            type: 'order_approved',
            title: 'Hasil kerja disetujui',
            message: 'Buyer menyetujui hasil pesanan #'.$order->id.'. Dana akan cair otomatis ke saldo dompet Anda 1 jam setelah penyelesaian.',
            extraData: [
                'order_id' => $order->id,
                'service_id' => $order->service_id,
            ]
        );

        return back()->with('success', 'Hasil kerja disetujui! Dana akan cair otomatis 1 jam setelah penyelesaian.');
    }

    /**
     * Buyer meminta revisi -> order kembali ke status dikerjakan.
     */
    public function requestRevision(Request $request, Order $order)
    {
        if ($order->buyer_id !== auth()->id()) {
            abort(403, 'Hanya buyer yang bisa meminta revisi.');
        }

        if ($order->status !== Order::STATUS_MENUNGGU_PERSETUJUAN) {
            abort(403, 'Revisi hanya bisa diminta setelah seller mengirim hasil.');
        }

        $validated = $request->validate([
            'revision_note' => 'required|string|max:500',
        ]);

        $order->update(['status' => Order::STATUS_DIKERJAKAN]);

        // Catatan revisi disimpan sebagai pesan diskusi, supaya seller langsung lihat alasannya
        $order->messages()->create([
            'sender_id' => auth()->id(),
            'message' => '[Minta Revisi] '.$validated['revision_note'],
        ]);

        return back()->with('success', 'Permintaan revisi terkirim ke seller.');
    }
}
