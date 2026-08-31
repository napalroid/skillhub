<?php

namespace App\Services;

use App\Models\EscrowTransaction;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EscrowService
{
    public function credit(Payment $payment, ?string $description = null): EscrowTransaction
    {
        $payment->loadMissing('order.service');

        $currentBalance = $this->getCurrentBalance();

        $transaction = EscrowTransaction::create([
            'payment_id' => $payment->id,
            'order_id' => $payment->order_id,
            'type' => EscrowTransaction::TYPE_IN,
            'amount' => $payment->amount,
            'balance_before' => $currentBalance,
            'balance_after' => $currentBalance + $payment->amount,
            'description' => $description ?? 'Pembayaran dari buyer via QRIS',
            'status' => EscrowTransaction::STATUS_PENDING,
            'expires_at' => now()->addHours(24),
        ]);

        $this->notifyAdminsPending($transaction, $payment);

        return $transaction;
    }

    public function confirmCredit(EscrowTransaction $transaction, User $admin): bool
    {
        if (!$transaction->isPending()) {
            return false;
        }

        $payment = $transaction->payment;
        if (!$payment || $payment->status !== 'paid') {
            return false;
        }

        DB::transaction(function () use ($transaction, $payment, $admin) {
            $transaction->update([
                'status' => EscrowTransaction::STATUS_COMPLETED,
                'processed_by' => $admin->id,
                'processed_at' => now(),
            ]);

            $fresh = Payment::whereKey($payment->id)->lockForUpdate()->firstOrFail();
            $fresh->update([
                'status' => 'verified',
                'verified_by' => $admin->id,
                'admin_confirmed_at' => now(),
                'admin_confirmed_by' => $admin->id,
            ]);

            $fresh->order()->update([
                'payment_status' => 'paid',
                'status' => 'dibayar',
                'paid_at' => $fresh->order->paid_at ?? now(),
            ]);
        });

        $this->notifySellerConfirmed($payment->order);
        $this->notifyBuyerConfirmed($payment->order);

        return true;
    }

    public function rejectCredit(EscrowTransaction $transaction, User $admin): bool
    {
        if (!$transaction->isPending()) {
            return false;
        }

        $payment = $transaction->payment;

        DB::transaction(function () use ($transaction, $payment, $admin) {
            $transaction->update([
                'status' => EscrowTransaction::STATUS_CANCELLED,
                'processed_by' => $admin->id,
                'processed_at' => now(),
                'description' => ($transaction->description ?? '') . ' (Ditolak admin)',
            ]);

            if ($payment) {
                $payment->update([
                    'status' => 'rejected',
                    'verified_by' => $admin->id,
                ]);

                $payment->order?->update(['status' => 'menunggu_pembayaran']);
            }
        });

        if ($payment && $payment->order) {
            $this->notifyBuyerRejected($payment->order);
        }

        return true;
    }

    public function debit(Order $order, ?string $description = null): ?EscrowTransaction
    {
        $payment = $order->payment;
        if (!$payment || !in_array($payment->status, ['verified', 'refunded'])) {
            return null;
        }

        $currentBalance = $this->getCurrentBalance();

        return EscrowTransaction::create([
            'payment_id' => $payment->id,
            'order_id' => $order->id,
            'type' => EscrowTransaction::TYPE_OUT,
            'amount' => $payment->amount,
            'balance_before' => $currentBalance,
            'balance_after' => max(0, $currentBalance - $payment->amount),
            'description' => $description ?? 'Pencairan escrow',
            'status' => EscrowTransaction::STATUS_COMPLETED,
            'processed_by' => auth()->id(),
            'processed_at' => now(),
        ]);
    }

    public function expireTransactions(): int
    {
        $expired = EscrowTransaction::query()
            ->overdue()
            ->with(['payment.order'])
            ->get();

        $count = 0;

        foreach ($expired as $transaction) {
            DB::transaction(function () use ($transaction, &$count) {
                $transaction->update([
                    'status' => EscrowTransaction::STATUS_EXPIRED,
                    'description' => ($transaction->description ?? '') . ' (Expired - melewati batas 24 jam)',
                ]);

                $payment = $transaction->payment;
                if ($payment && $payment->status === 'paid') {
                    $payment->update([
                        'status' => 'rejected',
                    ]);

                    $order = $payment->order;
                    if ($order) {
                        $order->update(['status' => 'menunggu_pembayaran']);

                        $buyer = $order->buyer;
                        if ($buyer) {
                            $oldBalance = $buyer->balance;
                            User::whereKey($buyer->id)->lockForUpdate()->increment('balance', $payment->amount);

                            WalletTransaction::create([
                                'user_id' => $buyer->id,
                                'type' => 'refund',
                                'amount' => $payment->amount,
                                'balance_before' => $oldBalance,
                                'balance_after' => $buyer->fresh()->balance,
                                'reference_type' => 'order',
                                'reference_id' => $order->id,
                                'description' => 'Refund otomatis - pembayaran expired',
                                'status' => 'completed',
                            ]);
                        }

                        $this->notifyBuyerExpired($order);
                        $this->notifyAdminExpired($transaction);
                    }
                }

                $count++;
            });
        }

        if ($count > 0) {
            Log::info("EscrowService: {$count} transaksi expired diproses.");
        }

        return $count;
    }

    public function getCurrentBalance(): float
    {
        $totalIn = EscrowTransaction::where('type', EscrowTransaction::TYPE_IN)
            ->where('status', EscrowTransaction::STATUS_COMPLETED)
            ->sum('amount');

        $totalOut = EscrowTransaction::where('type', EscrowTransaction::TYPE_OUT)
            ->where('status', EscrowTransaction::STATUS_COMPLETED)
            ->sum('amount');

        return max(0, (float) $totalIn - (float) $totalOut);
    }

    public function getPendingBalance(): float
    {
        return (float) EscrowTransaction::where('type', EscrowTransaction::TYPE_IN)
            ->where('status', EscrowTransaction::STATUS_PENDING)
            ->sum('amount');
    }

    protected function notifyAdminsPending(EscrowTransaction $transaction, Payment $payment): void
    {
        $order = $payment->order;
        $amount = $payment->amount;

        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationService::createAndDispatch(
                userId: $admin->id,
                type: 'escrow_pending',
                title: 'Saldo Escrow Baru Menunggu Konfirmasi',
                message: "Pembayaran Rp" . number_format($amount, 0, ',', '.') . " dari buyer menunggu konfirmasi Anda. Batas waktu 24 jam.",
                extraData: [
                    'escrow_transaction_id' => $transaction->id,
                    'payment_id' => $payment->id,
                    'order_id' => $order?->id,
                    'service_id' => $order?->service_id,
                    'expires_at' => $transaction->expires_at?->toIso8601String(),
                ]
            );
        }
    }

    protected function notifySellerConfirmed(Order $order): void
    {
        $order->loadMissing('service');
        $sellerId = $order->service?->user_id;

        if (!$sellerId) {
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

    protected function notifyBuyerConfirmed(Order $order): void
    {
        $order->loadMissing('service');
        if (!$order->buyer_id) {
            return;
        }

        NotificationService::createAndDispatch(
            userId: $order->buyer_id,
            type: 'order_escrow',
            title: 'Pembayaran dikonfirmasi admin',
            message: 'Pesanan #' . $order->id . ' "' . ($order->service?->title ?? 'jasa') . '" sudah dibayar & dikonfirmasi. Seller akan segera mengerjakan pesanan Anda.',
            extraData: [
                'order_id' => $order->id,
                'payment_id' => $order->payment?->id,
                'service_id' => $order->service_id,
            ]
        );
    }

    protected function notifyBuyerRejected(Order $order): void
    {
        $order->loadMissing('service');
        if (!$order->buyer_id) {
            return;
        }

        NotificationService::createAndDispatch(
            userId: $order->buyer_id,
            type: 'payment_rejected',
            title: 'Pembayaran ditolak',
            message: 'Pembayaran untuk pesanan #' . $order->id . ' ditolak admin. Silakan upload ulang bukti pembayaran.',
            extraData: [
                'order_id' => $order->id,
                'service_id' => $order->service_id,
            ]
        );
    }

    protected function notifyBuyerExpired(Order $order): void
    {
        $order->loadMissing('service');
        if (!$order->buyer_id) {
            return;
        }

        NotificationService::createAndDispatch(
            userId: $order->buyer_id,
            type: 'payment_expired',
            title: 'Pembayaran kadaluarsa',
            message: 'Pembayaran untuk pesanan #' . $order->id . ' kadaluarsa (melewati 24 jam). Dana telah dikembalikan ke dompet Anda.',
            extraData: [
                'order_id' => $order->id,
                'service_id' => $order->service_id,
            ]
        );
    }

    protected function notifyAdminExpired(EscrowTransaction $transaction): void
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            NotificationService::createAndDispatch(
                userId: $admin->id,
                type: 'escrow_expired',
                title: 'Transaksi escrow kadaluarsa',
                message: "Transaksi escrow #{$transaction->id} telah kadaluarsa dan dana dikembalikan ke buyer.",
                extraData: [
                    'escrow_transaction_id' => $transaction->id,
                    'payment_id' => $transaction->payment_id,
                    'order_id' => $transaction->order_id,
                ]
            );
        }
    }
}
