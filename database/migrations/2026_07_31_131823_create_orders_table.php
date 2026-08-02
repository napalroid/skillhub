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
    Schema::create('orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('service_id')->constrained();
        $table->foreignId('buyer_id')->constrained('users')->cascadeOnDelete();  // buyer
        $table->enum('status', [
            'menunggu_pembayaran',
            'menunggu_verifikasi',
            'dibayar (Dana akan di tahan selama proses pengerjaan)',
            'dikerjakan',
            'menunggu_persetujuan',
            'selesai',
        ])->default('menunggu_pembayaran');
        $table->decimal('final_price', 10, 2);
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
