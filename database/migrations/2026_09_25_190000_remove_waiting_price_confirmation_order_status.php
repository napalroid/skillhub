<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Existing standard orders previously waited for seller pricing without a final price.
        // Their displayed estimate is now the payable total, while manual Joki ML orders remain
        // protected by their separate approval_status field.
        DB::statement("UPDATE orders
            SET final_price = COALESCE(final_price, estimated_price, 0),
                approval_status = 'approved'
            WHERE status = 'menunggu_konfirmasi_harga'
              AND COALESCE(approval_status, 'approved') <> 'pending'");

        DB::statement("UPDATE orders
            SET status = 'menunggu_pembayaran'
            WHERE status = 'menunggu_konfirmasi_harga'");

        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'menunggu_pembayaran',
            'menunggu_verifikasi',
            'menunggu_konfirmasi',
            'dikonfirmasi',
            'dikerjakan',
            'menunggu_persetujuan',
            'selesai',
            'dibatalkan'
        ) DEFAULT 'menunggu_pembayaran'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'menunggu_pembayaran',
            'menunggu_verifikasi',
            'menunggu_konfirmasi_harga',
            'menunggu_konfirmasi',
            'dikonfirmasi',
            'dikerjakan',
            'menunggu_persetujuan',
            'selesai',
            'dibatalkan'
        ) DEFAULT 'menunggu_pembayaran'");

        DB::statement("UPDATE orders
            SET status = 'menunggu_konfirmasi_harga'
            WHERE approval_status = 'pending'");
    }
};
