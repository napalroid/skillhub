<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Buyer mengunggah bukti pembayaran untuk sebuah pesanan.
     */
    public function store(StorePaymentRequest $request)
    {
        $validated = $request->validated();

        $order = Order::findOrFail($validated['order_id']);

        // Pastikan yang upload memang buyer pemilik pesanan ini, bukan orang lain
        if ($order->buyer_id !== auth()->id()) {
            abort(403, 'Anda tidak berhak mengunggah bukti pembayaran untuk pesanan ini.');
        }

        // Cegah upload ganda selama masih ada pembayaran yang berstatus pending/verified
        if ($order->payment && $order->payment->status !== 'rejected') {
            return back()->with('error', 'Pesanan ini sudah memiliki bukti pembayaran.');
        }

        $path = $request->file('proof_file')->store('bukti-pembayaran', 'public');

        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'proof_file' => $path,
                'amount' => $validated['amount'],
                'status' => 'pending',
                'verified_by' => null,
            ]
        );

        $order->update(['status' => 'menunggu_verifikasi']);

        return back()->with('success', 'Bukti pembayaran berhasil diunggah, menunggu verifikasi admin.');
    }

    /**
     * Admin melihat daftar pembayaran yang menunggu verifikasi.
     */
    public function index()
    {
        $payments = Payment::where('status', 'pending')
            ->with(['order.service', 'order.buyer'])
            ->latest()
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Admin menyetujui pembayaran → dana masuk status escrow (ditahan).
     */
    public function verify(Payment $payment)
    {
        $payment->update([
            'status' => 'verified',
            'verified_by' => auth()->id(),
        ]);

        $payment->order->update(['status' => 'dibayar']);

        return back()->with('success', 'Pembayaran diverifikasi. Dana ditahan (escrow) untuk seller.');
    }

    /**
     * Admin menolak pembayaran (misal bukti tidak jelas/tidak sesuai nominal).
     */
    public function reject(Request $request, Payment $payment)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255',
        ]);

        $payment->update([
            'status' => 'rejected',
            'verified_by' => auth()->id(),
        ]);

        $payment->order->update(['status' => 'menunggu_pembayaran']);

        return back()->with('success', 'Pembayaran ditolak, buyer diminta upload ulang.')
            ->with('rejection_reason', $request->rejection_reason);
    }
}