<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Services\MidtransService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $order->load('service');

        // A refresh or a second click must never turn a valid payment state
        // into a 422 page. Paid orders and an active QR are simply shown
        // again; expired/failed QRIS payments may create a new QR.
        if ($order->payment_status === 'paid') {
            return redirect()->route('orders.payment.show', $order);
        }

        if ($order->status !== 'menunggu_pembayaran') {
            return redirect()->route('orders.payment.show', $order)
                ->with('error', 'Pesanan ini tidak berada pada tahap pembayaran.');
        }

        if (! in_array($order->payment_status, ['pending', 'expired', 'failed'], true)) {
            return redirect()->route('orders.payment.show', $order)
                ->with('error', 'Status pembayaran pesanan ini tidak dapat diproses.');
        }

        $activePayment = $order->payment;
        if ($activePayment?->qris_url && $activePayment->status === 'pending' && (! $activePayment->expires_at || $activePayment->expires_at->isFuture())) {
            return redirect()->route('orders.payment.show', $order);
        }

        try {
            $response = $midtrans->createQrisCharge($order);
        } catch (\Throwable $exception) {
            report($exception);

            $isConnectionProblem = str_contains($exception->getMessage(), 'CURL Error')
                || str_contains($exception->getMessage(), 'Could not connect');
            $isCertificateProblem = str_contains($exception->getMessage(), 'SSL certificate')
                || str_contains($exception->getMessage(), 'local issuer certificate');

            return back()->with('error', $isCertificateProblem
                ? 'QRIS belum dapat dibuat karena sertifikat HTTPS PHP belum valid. Hubungi administrator server untuk memasang CA bundle.'
                : ($isConnectionProblem
                ? 'QRIS belum dapat dibuat karena PHP tidak bisa terhubung ke server Midtrans. Periksa koneksi atau firewall komputer server.'
                : 'Midtrans menolak pembuatan QRIS. Periksa kredensial Sandbox dan konfigurasi QRIS akun Midtrans.'));
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
        if (! $midtrans->isValidSignature($payload)) {
            Log::warning('Midtrans webhook rejected: invalid signature.', [
                'order_id' => $payload['order_id'] ?? null,
                'transaction_status' => $payload['transaction_status'] ?? null,
            ]);

            abort(403, 'Invalid Midtrans signature.');
        }

        $order = Order::where('midtrans_order_id', $payload['order_id'])->firstOrFail();
        abort_unless((int) round((float) $payload['gross_amount']) === (int) round((float) $order->final_price), 422, 'Nominal tidak sesuai.');

        $this->syncMidtransPayment($order, $payload);

        return response()->json(['ok' => true, 'transaction_status' => $payload['transaction_status'] ?? null]);
    }

    /**
     * Buyer memeriksa status QRIS secara langsung ke Midtrans. Berfungsi
     * sebagai fallback bila webhook server-to-server gagal menjangkau server
     * (misal tunnel ngrok terputus), sehingga admin tetap menerima transaksi
     * yang sudah dibayar.
     */
    public function checkStatus(Order $order, MidtransService $midtrans)
    {
        abort_unless($order->buyer_id === auth()->id() || $order->service->user_id === auth()->id() || auth()->user()->isAdmin(), 403);
        abort_unless((bool) $order->midtrans_order_id, 404, 'Pesanan ini belum memiliki QRIS Midtrans.');

        $payload = $midtrans->getStatus($order->midtrans_order_id);
        if (empty($payload)) {
            return response()->json(['ok' => false, 'message' => 'Tidak dapat menghubungi Midtrans.'], 502);
        }

        $result = $this->syncMidtransPayment($order, $payload);

        return response()->json([
            'ok' => true,
            'payment_status' => $result['payment_status'],
            'order_status' => $result['order_status'],
        ]);
    }

    /**
     * Sinkronisasi status order & payment berdasarkan payload Midtrans
     * (baik dari webhook maupun pengecekan status langsung). Mengembalikan
     * array berisi payment_status dan order_status terbaru.
     */
    protected function syncMidtransPayment(Order $order, array $payload): array
    {
        $transactionStatus = $payload['transaction_status'] ?? '';
        $paymentStatus = match ($transactionStatus) {
            'settlement', 'capture' => 'paid',
            'pending' => 'pending',
            'expire' => 'expired',
            'deny', 'cancel', 'failure' => 'failed',
            default => 'pending',
        };

        $payment = null;
        $finalPaymentStatus = $paymentStatus;
        $finalOrderStatus = $order->status;
        // Jangan mundurkan status yang sudah lebih lanjut (verified/released)
        // hanya karena webhook/resend mengirim status sebelumnya.
        $statusRank = ['pending' => 1, 'failed' => 1, 'expired' => 1, 'paid' => 2, 'verified' => 3, 'released' => 4];
        $currentPaymentStatus = $order->payment?->status;
        $effectiveStatus = $paymentStatus;
        if ($currentPaymentStatus && ($statusRank[$currentPaymentStatus] ?? 0) > ($statusRank[$paymentStatus] ?? 0)) {
            $effectiveStatus = $currentPaymentStatus;
        }
        DB::transaction(function () use ($order, $payload, $effectiveStatus, &$payment, &$finalPaymentStatus, &$finalOrderStatus) {
            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'amount' => $order->final_price,
                    'status' => $effectiveStatus,
                    'gateway_transaction_id' => $payload['transaction_id'] ?? $order->payment?->gateway_transaction_id,
                    'payment_type' => $payload['payment_type'] ?? $order->payment?->payment_type ?? 'qris',
                    'gateway_response' => $payload,
                ]
            );

            $updatedOrder = Order::whereKey($order->id)->lockForUpdate()->firstOrFail();
            $previouslyPaid = $updatedOrder->payment_status === 'paid';
            $updates = ['payment_status' => $effectiveStatus];
            if ($effectiveStatus === 'paid') {
                $updates += ['status' => 'dibayar', 'paid_at' => $updatedOrder->paid_at ?? now()];
            }
            if ($effectiveStatus === 'expired' || $effectiveStatus === 'failed') {
                $updates['status'] = 'menunggu_pembayaran';
            }
            $updatedOrder->update($updates);
            $order->setRawAttributes($updatedOrder->getAttributes(), true);
            $finalOrderStatus = $updatedOrder->status;

            if ($effectiveStatus === 'paid' && ! $previouslyPaid) {
                $order->loadMissing('service');
                $adminIds = User::where('role', 'admin')->pluck('id');
                foreach ($adminIds as $adminId) {
                    NotificationService::createAndDispatch(
                        userId: $adminId,
                        type: 'payment_paid',
                        title: 'Jasa terbayarkan — perlu konfirmasi saldo',
                        message: 'QRIS #'.$order->id.' "'.$order->service->title.'" lunas Rp'.number_format($order->final_price, 0, ',', '.').'. Konfirmasi bahwa dana masuk ke saldo admin.',
                        extraData: [
                            'order_id' => $order->id,
                            'payment_id' => $payment->id,
                            'service_id' => $order->service_id,
                        ]
                    );
                }
            }
        });

        $finalPaymentStatus = $effectiveStatus;

        Log::info('Midtrans payment synced.', [
            'order_id' => $order->id,
            'midtrans_order_id' => $payload['order_id'] ?? $order->midtrans_order_id,
            'transaction_status' => $transactionStatus,
            'payment_status' => $finalPaymentStatus,
        ]);

        return ['payment_status' => $finalPaymentStatus, 'order_status' => $finalOrderStatus];
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

        // Beri tahu admin (muncul di halaman Transaksi) bahwa ada bukti
        // pembayaran baru yang perlu diverifikasi.
        $order->loadMissing('service');
        $adminIds = User::where('role', 'admin')->pluck('id');
        foreach ($adminIds as $adminId) {
            NotificationService::createAndDispatch(
                userId: $adminId,
                type: 'payment_paid',
                title: 'Bukti pembayaran menunggu verifikasi',
                message: 'Buyer mengunggah bukti pembayaran untuk Pesanan #'.$order->id.' "'.$order->service->title.'". Silakan verifikasi.',
                extraData: [
                    'order_id' => $order->id,
                    'payment_id' => $order->payment?->id,
                    'service_id' => $order->service_id,
                ]
            );
        }

        return back()->with('success', 'Bukti pembayaran berhasil diunggah, menunggu verifikasi admin.');
    }

    /**
     * Admin melihat daftar transaksi & escrow.
     *
     * Kolom:
     *  - all         : Semua transaksi
     *  - verification: Verifikasi Pembayaran (belum terkonfirmasi: pending bukti + QRIS lunas menunggu konfirmasi saldo)
     *  - escrow      : Proses Tahan Dana (sudah diverifikasi admin, dana ditahan)
     *  - cair        : Cair (dana sudah dicairkan ke seller setelah jasa selesai)
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'verification');
        $allowedFilters = ['all', 'verification', 'escrow', 'cair'];
        if (! in_array($filter, $allowedFilters, true)) {
            $filter = 'verification';
        }

        $sort = $request->query('sort', 'latest');
        $allowedSorts = ['latest', 'oldest', 'amount_desc', 'amount_asc', 'status'];
        if (! in_array($sort, $allowedSorts, true)) {
            $sort = 'latest';
        }

        $counts = [
            'all' => Payment::count(),
            'verification' => Payment::whereIn('status', ['pending', 'paid'])->count(),
            'escrow' => Payment::where('status', 'verified')->count(),
            'cair' => Payment::where('status', 'released')->count(),
        ];

        $escrowBalance = Payment::where('status', 'verified')->sum('amount');
        $awaitingConfirm = Payment::where('status', 'paid')->count();
        $pendingProof = Payment::where('status', 'pending')->count();

        $query = Payment::with(['order.service.seller', 'order.buyer', 'verifier']);

        if ($filter === 'verification') {
            $query->whereIn('status', ['pending', 'paid']);
        } elseif ($filter === 'escrow') {
            $query->where('status', 'verified');
        } elseif ($filter === 'cair') {
            $query->where('status', 'released');
        }

        // Persortiran (urutan baris) — tombol filter ada di kolom 1,
        // pengurutan hasil diterapkan di sini dan ditampilkan di kolom 2.
        match ($sort) {
            'oldest' => $query->oldest(),
            'amount_desc' => $query->orderByDesc('amount'),
            'amount_asc' => $query->orderBy('amount'),
            'status' => $query->orderByRaw("FIELD(status, 'paid', 'pending', 'verified', 'released', 'rejected')")
                ->latest(),
            default => $query->latest(),
        };

        $payments = $query->paginate(15)->withQueryString();

        return view('admin.payments.index', compact(
            'payments', 'filter', 'sort', 'counts', 'escrowBalance', 'awaitingConfirm', 'pendingProof'
        ));
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

        $payment->loadMissing('order.service');
        $this->notifySellerOrderConfirmed($payment->order);
        $this->notifyBuyerOrderConfirmed($payment->order);

        return redirect()->route('admin.payments.index', ['filter' => 'escrow'])
            ->with('success', 'Pembayaran diverifikasi. Dana sudah ditahan di Proses Tahan Dana.');
    }

    /**
     * Notifikasi ke seller bahwa pesanan sudah lunas & dikonfirmasi,
     * sehingga seller bisa mulai memproses pesanan. Berlaku untuk semua
     * jalur order (pesan harga langsung maupun penawaran dari chat).
     */
    protected function notifySellerOrderConfirmed(Order $order): void
    {
        $order->loadMissing('service');
        $sellerId = $order->service?->user_id;

        if (! $sellerId) {
            return;
        }

        $already = \DB::table('user_notifications')
            ->where('user_id', $sellerId)
            ->where('type', 'order_confirmed')
            ->where('order_id', $order->id)
            ->exists();
        if ($already) {
            return;
        }

        NotificationService::createAndDispatch(
            userId: $sellerId,
            type: 'order_confirmed',
            title: 'Pesanan jasa sudah dikonfirmasi',
            message: 'Pemesanan jasa sudah terbayarkan dan sudah dikonfirmasi, kamu bisa mulai untuk memproses pesanan.',
            extraData: [
                'order_id' => $order->id,
                'payment_id' => $order->payment?->id,
                'service_id' => $order->service_id,
            ]
        );
    }

    /**
     * Notifikasi ke buyer bahwa pembayarannya sudah dikonfirmasi admin
     * dan seller akan segera mengerjakan pesanannya.
     */
    protected function notifyBuyerOrderConfirmed(Order $order): void
    {
        $order->loadMissing('service');
        if (! $order->buyer_id) {
            return;
        }

        $already = \DB::table('user_notifications')
            ->where('user_id', $order->buyer_id)
            ->where('type', 'order_escrow')
            ->where('order_id', $order->id)
            ->exists();
        if ($already) {
            return;
        }

        NotificationService::createAndDispatch(
            userId: $order->buyer_id,
            type: 'order_escrow',
            title: 'Pembayaran dikonfirmasi admin',
            message: 'Pesanan #'.$order->id.' "'.($order->service?->title ?? 'jasa').'" sudah dibayar & dikonfirmasi. Seller akan segera mengerjakan pesanan Anda.',
            extraData: [
                'order_id' => $order->id,
                'payment_id' => $order->payment?->id,
                'service_id' => $order->service_id,
            ]
        );
    }

    public function confirmBalance(Payment $payment)
    {
        abort_unless(auth()->user()->isAdmin(), 403);
        $payment->loadMissing('order.service');

        if ($payment->status !== 'paid') {
            return back()->with('error', 'Hanya pembayaran QRIS yang sudah settlement yang bisa dikonfirmasi.');
        }

        if ($payment->isAdminConfirmed()) {
            return back()->with('error', 'Saldo untuk transaksi ini sudah dikonfirmasi sebelumnya.');
        }

        DB::transaction(function () use ($payment) {
            $fresh = Payment::whereKey($payment->id)->lockForUpdate()->firstOrFail();
            if ($fresh->isAdminConfirmed()) {
                return;
            }

            $fresh->update([
                'status' => 'verified',
                'verified_by' => auth()->id(),
                'admin_confirmed_at' => now(),
                'admin_confirmed_by' => auth()->id(),
            ]);

            $fresh->order()->update([
                'payment_status' => 'paid',
                'status' => 'dibayar',
                'paid_at' => $fresh->order->paid_at ?? now(),
            ]);
        });

        $payment->loadMissing('order.service');
        $this->notifySellerOrderConfirmed($payment->order);
        $this->notifyBuyerOrderConfirmed($payment->order);

        return back()->with('success', 'Saldo dikonfirmasi masuk. Seller telah diberi notifikasi untuk mengerjakan pesanan.');
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
