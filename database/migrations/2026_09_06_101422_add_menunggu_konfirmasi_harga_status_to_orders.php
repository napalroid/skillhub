<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
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
        ) DEFAULT 'menunggu_konfirmasi_harga'");
    }

    public function down(): void
    {
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
};
