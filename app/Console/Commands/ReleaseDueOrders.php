<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReleaseDueOrders extends Command
{
    protected $signature = 'orders:release-due';

    protected $description = 'Proses auto-complete (buyer diam 3 hari) & pencairan escrow otomatis (1 jam setelah selesai).';

    public function handle(): int
    {
        $this->autoComplete();
        $this->releaseDue();

        return self::SUCCESS;
    }

    /**
     * C4: Bila seller sudah mengirim hasil namun buyer diam 3 hari,
     * pesanan otomatis diselesaikan agar seller tidak tertahan dananya.
     */
    protected function autoComplete(): void
    {
        $orders = Order::where('status', Order::STATUS_MENUNGGU_PERSETUJUAN)
            ->whereHas('files', fn ($q) => $q->where('file_type', 'hasil'))
            ->with('files')
            ->get();

        $count = 0;
        foreach ($orders as $order) {
            $delivered = $order->files->where('file_type', 'hasil')->max('created_at');
            if ($delivered && $delivered->lte(now()->subDays(3))) {
                $order->update([
                    'status' => Order::STATUS_SELESAI,
                    'completed_at' => now(),
                ]);

                UserNotification::create([
                    'user_id' => $order->buyer_id,
                    'order_id' => $order->id,
                    'service_id' => $order->service_id,
                    'type' => 'order_auto_completed',
                    'title' => 'Pesanan otomatis selesai',
                    'message' => 'Pesanan #'.$order->id.' otomatis diselesaikan karena tidak ada tanggapan dalam 3 hari. Dana akan cair ke seller.',
                    'is_read' => false,
                ]);

                $count++;
            }
        }

        if ($count) {
            $this->info("Auto-complete: {$count} pesanan diselesaikan.");
        }
    }

    /**
     * Pencairan otomatis: order selesai >= 1 jam & escrow masih 'verified'.
     * Dana masuk ke saldo dompet seller (amount = payment.amount).
     */
    protected function releaseDue(): void
    {
        $orders = Order::where('status', Order::STATUS_SELESAI)
            ->whereNotNull('completed_at')
            ->where('completed_at', '<=', now()->subHour())
            ->with('payment', 'service')
            ->get();

        $count = 0;
        foreach ($orders as $order) {
            $payment = $order->payment;
            if (! $payment || $payment->status !== 'verified') {
                continue; // sudah dirilis / dikembalikan / belum di-escrow
            }

            $seller = $order->service?->seller;
            if (! $seller) {
                continue;
            }

            DB::transaction(function () use ($payment, $seller, $order, &$count) {
                $fresh = Payment::whereKey($payment->id)->lockForUpdate()->firstOrFail();
                if ($fresh->status !== 'verified') {
                    return; // idempoten: sudah diproses di Runnable lain
                }

                $fresh->update([
                    'status' => 'released',
                    'released_at' => now(),
                ]);

                User::whereKey($seller->id)->lockForUpdate()->increment('balance', $fresh->amount);

                UserNotification::create([
                    'user_id' => $seller->id,
                    'order_id' => $order->id,
                    'payment_id' => $fresh->id,
                    'service_id' => $order->service_id,
                    'type' => 'payout_released',
                    'title' => 'Dana pesanan cair',
                    'message' => 'Dana pesanan #'.$order->id.' "'.($order->service?->title ?? 'jasa').'" sebesar Rp'.number_format($fresh->amount, 0, ',', '.').' telah cair ke saldo dompet Anda.',
                    'is_read' => false,
                ]);

                $count++;
            });
        }

        if ($count) {
            $this->info("Pencairan: {$count} pesanan dicairkan ke seller.");
        }
    }
}
