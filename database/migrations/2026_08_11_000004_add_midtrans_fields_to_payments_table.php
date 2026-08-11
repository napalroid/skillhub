<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('proof_file')->nullable()->change();
            $table->string('gateway_transaction_id')->nullable()->unique()->after('order_id');
            $table->string('payment_type', 30)->nullable()->after('gateway_transaction_id');
            $table->text('qris_url')->nullable()->after('payment_type');
            $table->json('gateway_response')->nullable()->after('qris_url');
            $table->timestamp('expires_at')->nullable()->after('gateway_response');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique(['gateway_transaction_id']);
            $table->dropColumn(['gateway_transaction_id', 'payment_type', 'qris_url', 'gateway_response', 'expires_at']);
            $table->string('proof_file')->nullable(false)->change();
        });
    }
};
