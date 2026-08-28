<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WalletTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->take(5)->get();

        foreach ($users as $user) {
            $balance = 0;

            $transactions = [
                ['type' => 'credit', 'amount' => 150000, 'description' => 'Pesanan selesai - Jasa Editing Video', 'reference_type' => 'order', 'reference_id' => 1, 'days_ago' => 45],
                ['type' => 'credit', 'amount' => 200000, 'description' => 'Pesanan selesai - Jasa Tutor Matematika', 'reference_type' => 'order', 'reference_id' => 2, 'days_ago' => 40],
                ['type' => 'debit', 'amount' => 100000, 'description' => 'Penarikan ke E-wallet', 'reference_type' => 'payout_request', 'reference_id' => 1, 'days_ago' => 35],
                ['type' => 'credit', 'amount' => 300000, 'description' => 'Pesanan selesai - Jasa Desain Grafis', 'reference_type' => 'order', 'reference_id' => 3, 'days_ago' => 30],
                ['type' => 'credit', 'amount' => 175000, 'description' => 'Pesanan selesai - Jasa Fotografi Produk', 'reference_type' => 'order', 'reference_id' => 4, 'days_ago' => 25],
                ['type' => 'debit', 'amount' => 250000, 'description' => 'Penarikan ke E-wallet', 'reference_type' => 'payout_request', 'reference_id' => 2, 'days_ago' => 20],
                ['type' => 'credit', 'amount' => 225000, 'description' => 'Pesanan selesai - Jasa Website Builder', 'reference_type' => 'order', 'reference_id' => 5, 'days_ago' => 15],
                ['type' => 'refund', 'amount' => 100000, 'description' => 'Pengembalian Dana - Pembatalan Pesanan', 'reference_type' => 'order', 'reference_id' => 6, 'days_ago' => 10],
                ['type' => 'credit', 'amount' => 325000, 'description' => 'Pesanan selesai - Jasa Content Writing', 'reference_type' => 'order', 'reference_id' => 7, 'days_ago' => 5],
                ['type' => 'debit', 'amount' => 150000, 'description' => 'Penarikan ke Rekening Bank', 'reference_type' => 'payout_request', 'reference_id' => 3, 'days_ago' => 2],
            ];

            foreach ($transactions as $trans) {
                $daysAgo = $trans['days_ago'];
                unset($trans['days_ago']);

                if ($trans['type'] === 'credit' || $trans['type'] === 'refund') {
                    $balance += $trans['amount'];
                } else {
                    $balance -= $trans['amount'];
                }

                WalletTransaction::create([
                    'user_id' => $user->id,
                    'type' => $trans['type'],
                    'amount' => $trans['amount'],
                    'balance_before' => $balance - ($trans['type'] === 'credit' || $trans['type'] === 'refund' ? $trans['amount'] : -$trans['amount']),
                    'balance_after' => $balance,
                    'reference_type' => $trans['reference_type'],
                    'reference_id' => $trans['reference_id'],
                    'description' => $trans['description'],
                    'status' => 'completed',
                    'created_at' => now()->subDays($daysAgo),
                    'updated_at' => now()->subDays($daysAgo),
                ]);
            }
        }
    }
}
