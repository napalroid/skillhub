<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Bersihkan enum orders.status:
        //    - hapus nilai rusak 'dibayar (Dana akan di tahan selama proses pengerjaan)'
        //    - tambah 'dibatalkan' (untuk refund / cancel)
        DB::statement("ALTER TABLE orders MODIFY status ENUM(
            'menunggu_pembayaran',
            'menunggu_verifikasi',
            'dibayar',
            'dikerjakan',
            'menunggu_persetujuan',
            'selesai',
            'dibatalkan'
        ) NOT NULL DEFAULT 'menunggu_pembayaran'");

        // 2. Kolom completed_at (waktu buyer menekan "Selesaikan Pesanan")
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('completed_at')->nullable()->after('final_price');
        });

        // 3. Dompet saldo seller (dan bisa juga buyer) pada tabel users
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('balance', 12, 2)->default(0)->after('phone');
        });

        // 4. Enum payments.status: tambah 'refunded' (escrow dikembalikan ke buyer)
        DB::statement("ALTER TABLE payments MODIFY status ENUM(
            'pending',
            'paid',
            'verified',
            'released',
            'rejected',
            'refunded',
            'expired',
            'failed'
        ) NOT NULL DEFAULT 'pending'");

        // 5. Audit pencairan
        Schema::table('payments', function (Blueprint $table) {
            $table->timestamp('released_at')->nullable()->after('admin_confirmed_at');
            $table->foreignId('released_by')->nullable()->after('released_at')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY status ENUM(
            'menunggu_pembayaran',
            'menunggu_verifikasi',
            'dibayar (Dana akan di tahan selama proses pengerjaan)',
            'dibayar',
            'dikerjakan',
            'menunggu_persetujuan',
            'selesai'
        ) NOT NULL DEFAULT 'menunggu_pembayaran'");

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('completed_at');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('balance');
        });

        DB::statement("ALTER TABLE payments MODIFY status ENUM(
            'pending',
            'paid',
            'verified',
            'released',
            'rejected',
            'expired',
            'failed'
        ) NOT NULL DEFAULT 'pending'");

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['released_by']);
            $table->dropColumn(['released_at', 'released_by']);
        });
    }
};
