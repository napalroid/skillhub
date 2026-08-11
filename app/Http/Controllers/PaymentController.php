<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Services\MidtransService;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function showQris(Order $order)
    {
        abort_unless($order->buyer_id === auth()->id() || $order->service->user_id === auth()->id() || auth()->user()->isAdmin(), 403);
        $order->load(['service.seller', 'payment', 'priceOffer']);

        return view('payments.qris', compact('order'));
    }

    public function createQris(Order $order, MidtransService $midtrans)
    {
        abort_unless($order->buyer_id === auth()->id(), 403);
        abort_unless($order->status === 'menunggu_pembayaran' && $order->payment_status === 'pending', 422, 'Order tidak dapat dibayar.');
        $order->load('service');

        $activePayment = $order->payment;
        if ($activePayment?->qris_url && $activePayment->status === 'pending' && (! $activePayment->expires_at || $activePayment->expires_at->isFuture())) {
            return redirect()->route('orders.payment.show', $order);
        }

        try {
            $response = $midtrans->createQrisCharge($order);
        } catch (\Throwable $exception) {
            report($exception);
            return back()->with('error', 'QRIS tidak dapat dibuat. Periksa kredensial Midtrans Sandbox.');
        }

        $qrisUrl = data_get(collect($response['actions'] ?? [])->firstWhere('name', 'generate-qr-code'), 'url');
        if (! $qrisUrl || empty($response['transaction_id'])) {
            return back()->with('error', 'Midtrans tidak mengembalikan QRIS yang valid.');
        }

        DB::transaction(function () use ($order, $response, $qrisUrl) {
            $order->update(['midtrans_order_id' => $response['order_id']]);
            Payment::updateOrCreate(['order_id' => $order->id], [
                'amount' => $order->final_price,
                'status' => 'pending',
                'gateway_transaction_id' => $response['transaction_id'],
                'payment_type' => 'qris',
                'qris_url' => $qrisUrl,
                'gateway_response' => $response,
                'expires_at' => isset($response['expiry_time']) ? now()->parse($response['expiry_time']) : null,
                'proof_file' => null,
                'verified_by' => null,
            ]);
        });

        return redirect()->route('orders.payment.show', $order);
    }

    public function notification(Request $request, MidtransService $midtrans)
    {
        $payload = $request->all();
        abort_unless($midtrans->isValidSignature($payload), 403, 'Invalid Midtrans signature.');
        $order = Order::where('midtrans_order_id', $payload['order_id'])->firstOrFail();
        abort_unless((int) round((float) $payload['gross_amount']) === (int) round((float) $order->final_price), 422, 'Nominal tidak sesuai.');

        $transactionStatus = $payload['transaction_status'] ?? '';
        $paymentStatus = match ($transactionStatus) { 'settlement', 'capture' => 'paid', 'pending' => 'pending', 'expire' => 'expired', 'deny', 'cancel', 'failure' => 'failed', default => 'pending' };
        DB::transaction(function () use ($order, $payload, $paymentStatus, $transactionStatus) {
            Payment::updateOrCreate(['order_id' => $order->id], ['amount' => $order->final_price, 'status' => $paymentStatus, 'gateway_transaction_id' => $payload['transaction_id'] ?? null, 'payment_type' => $payload['payment_type'] ?? 'qris', 'gateway_response' => $payload]);
            $updates = ['payment_status' => $paymentStatus];
            if ($paymentStatus === 'paid') { $updates += ['status' => 'dibayar (Dana akan di tahan selama proses pengerjaan)', 'paid_at' => now()]; }
            if ($paymentStatus === 'expired' || $paymentStatus === 'failed') { $updates['status'] = 'menunggu_pembayaran'; }
            $order->update($updates);
        });

        return response()->json(['ok' => true, 'transaction_status' => $transactionStatus]);
    }
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
