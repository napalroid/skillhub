<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderFile;
use App\Models\Payment;
use App\Models\Service;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Database\Seeder;

/**
 * Data dummy untuk menguji alur escrow + pencairan.
 * Jalankan: php artisan db:seed --class=OrderFlowSeeder
 * Lalu uji scheduler: php artisan orders:release-due
 */
class OrderFlowSeeder extends Seeder
{
    public function run(): void
    {
        $seller = User::where('email', 'seller@example.com')->firstOrFail();
        $buyer = User::where('email', 'buyer@example.com')->firstOrFail();

        $webService = Service::where('title', 'Website Landing Page React + Tailwind')
            ->where('user_id', $seller->id)->firstOrFail();
        $logoService = Service::where('title', 'Desain Logo Profesional Modern')
            ->where('user_id', $seller->id)->firstOrFail();

        // Baseline saldo agar seller bisa langsung test penarikan mandiri.
        $seller->update(['balance' => 500000, 'payout_type' => 'gopay', 'payout_account' => '081234567891', 'payout_account_name' => 'Joko Seller']);

        // Cleanup data test sebelumnya agar seeder idempoten (status order bisa berubah
        // akibat orders:release-due, sehingga firstOrCreate perlu data bersih).
        $testOrderIds = Payment::where('gateway_transaction_id', 'like', 'TEST-%')->pluck('order_id')->unique();
        Order::whereIn('id', $testOrderIds)->each(function ($order) {
            $order->files()->delete();
            $order->delete();
        });
        Payment::where('gateway_transaction_id', 'like', 'TEST-%')->delete();

        // 1) WEBSITE > 3 HARI: masih dikerjakan, hasil BELUM diupload.
        //    Escrow tetap aman, TIDAK memicu auto-complete (timer mulai dari upload hasil).
        $webWip = Order::firstOrCreate(
            ['buyer_id' => $buyer->id, 'service_id' => $webService->id, 'status' => Order::STATUS_DIKERJAKAN, 'final_price' => $webService->price],
            ['final_price' => $webService->price, 'payment_status' => 'paid']
        );
        Payment::firstOrCreate(['order_id' => $webWip->id], [
            'amount' => $webService->price, 'status' => 'verified', 'payment_type' => 'qris',
            'gateway_transaction_id' => 'TEST-WEB-WIP', 'admin_confirmed_at' => now(),
        ]);

        // 2) menunggu_persetujuan, hasil diupload 4 HARI lalu -> auto-complete saat `orders:release-due`.
        $autoComplete = Order::firstOrCreate(
            ['buyer_id' => $buyer->id, 'service_id' => $logoService->id, 'status' => Order::STATUS_MENUNGGU_PERSETUJUAN, 'final_price' => $logoService->price],
            ['final_price' => $logoService->price, 'payment_status' => 'paid']
        );
        Payment::firstOrCreate(['order_id' => $autoComplete->id], [
            'amount' => $logoService->price, 'status' => 'verified', 'payment_type' => 'qris',
            'gateway_transaction_id' => 'TEST-AUTOCOMPLETE', 'admin_confirmed_at' => now(),
        ]);
        $this->hasil($autoComplete, $seller, now()->subDays(4));

        // 3) menunggu_persetujuan, hasil diupload 1 HARI lalu -> BELUM auto-complete (masih dalam 3 hari).
        $withinWindow = Order::firstOrCreate(
            ['buyer_id' => $buyer->id, 'service_id' => $webService->id, 'status' => Order::STATUS_MENUNGGU_PERSETUJUAN, 'final_price' => $webService->price],
            ['final_price' => $webService->price, 'payment_status' => 'paid']
        );
        Payment::firstOrCreate(['order_id' => $withinWindow->id], [
            'amount' => $webService->price, 'status' => 'verified', 'payment_type' => 'qris',
            'gateway_transaction_id' => 'TEST-WITHIN', 'admin_confirmed_at' => now(),
        ]);
        $this->hasil($withinWindow, $seller, now()->subDay());

        // 4) selesai, completed_at 2 JAM lalu, escrow masih verified -> auto-release (saldo seller +Rp150.000).
        $release = Order::firstOrCreate(
            ['buyer_id' => $buyer->id, 'service_id' => $logoService->id, 'status' => Order::STATUS_SELESAI, 'final_price' => $logoService->price],
            ['final_price' => $logoService->price, 'payment_status' => 'paid', 'completed_at' => now()->subHours(2)]
        );
        Payment::firstOrCreate(['order_id' => $release->id], [
            'amount' => $logoService->price, 'status' => 'verified', 'payment_type' => 'qris',
            'gateway_transaction_id' => 'TEST-RELEASE', 'admin_confirmed_at' => now(),
        ]);

        // 5) dibayar (escrow ditahan, belum dikerjakan) -> memicu tombol "Mulai Kerjakan" & "Upload Hasil".
        $paid = Order::firstOrCreate(
            ['buyer_id' => $buyer->id, 'service_id' => $logoService->id, 'status' => Order::STATUS_DIBAYAR, 'final_price' => $logoService->price],
            ['final_price' => $logoService->price, 'payment_status' => 'paid']
        );
        Payment::firstOrCreate(['order_id' => $paid->id], [
            'amount' => $logoService->price, 'status' => 'verified', 'payment_type' => 'qris',
            'gateway_transaction_id' => 'TEST-PAID', 'admin_confirmed_at' => now(),
        ]);

        // Notifikasi contoh ke seller agar terlihat di UI.
        UserNotification::firstOrCreate(
            ['user_id' => $seller->id, 'type' => 'payout_released', 'title' => 'Contoh notifikasi'],
            ['message' => 'Ini notifikasi contoh untuk test UI dompet/seller.', 'is_read' => false]
        );

        $this->command->info('OrderFlowSeeder selesai. Login seller@example.com / buyer@example.com (password123).');
        $this->command->info('Jalankan: php artisan orders:release-due  (uji auto-complete & auto-release).');
    }

    private function hasil(Order $order, User $seller, $when): void
    {
        $file = OrderFile::firstOrCreate(
            ['order_id' => $order->id, 'file_type' => 'hasil'],
            ['uploader_id' => $seller->id, 'file_path' => 'order-files/test-hasil.txt']
        );
        $file->update(['created_at' => $when, 'updated_at' => $when]);
    }
}
