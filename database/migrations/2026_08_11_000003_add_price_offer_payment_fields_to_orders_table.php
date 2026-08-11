<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('price_offer_id')->nullable()->after('buyer_id')->constrained()->nullOnDelete();
            $table->string('payment_status', 20)->default('pending')->after('status');
            $table->string('midtrans_order_id')->nullable()->unique()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('midtrans_order_id');
            $table->unique('price_offer_id');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique(['price_offer_id']);
            $table->dropUnique(['midtrans_order_id']);
            $table->dropConstrainedForeignId('price_offer_id');
            $table->dropColumn(['payment_status', 'midtrans_order_id', 'paid_at']);
        });
    }
};
