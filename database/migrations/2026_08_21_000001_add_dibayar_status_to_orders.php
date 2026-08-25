<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [
                'menunggu_pembayaran',
                'menunggu_verifikasi',
                'dibayar',
                'dibayar (Dana akan di tahan selama proses pengerjaan)',
                'dikerjakan',
                'menunggu_persetujuan',
                'selesai',
            ])->default('menunggu_pembayaran')->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [
                'menunggu_pembayaran',
                'menunggu_verifikasi',
                'dibayar (Dana akan di tahan selama proses pengerjaan)',
                'dikerjakan',
                'menunggu_persetujuan',
                'selesai',
            ])->default('menunggu_pembayaran')->change();
        });
    }
};
