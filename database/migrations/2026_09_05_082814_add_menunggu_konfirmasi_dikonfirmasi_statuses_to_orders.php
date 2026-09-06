<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status_new', 30)->nullable()->after('status');
        });

        DB::statement("
            UPDATE orders 
            SET status_new = CASE 
                WHEN status = 'dibayar' AND EXISTS (
                    SELECT 1 FROM payments 
                    WHERE payments.order_id = orders.id 
                    AND payments.admin_confirmed_at IS NOT NULL
                ) THEN 'dikonfirmasi'
                WHEN status = 'dibayar' THEN 'menunggu_konfirmasi'
                ELSE status
            END
        ");

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->renameColumn('status_new', 'status');
        });

        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'menunggu_pembayaran',
            'menunggu_verifikasi',
            'menunggu_konfirmasi',
            'dikonfirmasi',
            'dikerjakan',
            'menunggu_persetujuan',
            'selesai',
            'dibatalkan'
        ) NOT NULL DEFAULT 'menunggu_pembayaran'");
    }

    public function down(): void
    {
        DB::statement("
            UPDATE orders 
            SET status = 'dibayar'
            WHERE status IN ('menunggu_konfirmasi', 'dikonfirmasi')
        ");

        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM(
            'menunggu_pembayaran',
            'menunggu_verifikasi',
            'dibayar',
            'dikerjakan',
            'menunggu_persetujuan',
            'selesai',
            'dibatalkan'
        ) NOT NULL DEFAULT 'menunggu_pembayaran'");
    }
};
