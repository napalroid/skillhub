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
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('approved')->after('status');
            $table->timestamp('seller_approved_at')->nullable()->after('approval_status');
            $table->text('seller_rejection_reason')->nullable()->after('seller_approved_at');
            $table->string('orderable_type')->nullable()->after('seller_rejection_reason');
            $table->unsignedBigInteger('orderable_id')->nullable()->after('orderable_type');
            $table->index(['orderable_type', 'orderable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['orderable_type', 'orderable_id']);
            $table->dropColumn(['approval_status', 'seller_approved_at', 'seller_rejection_reason', 'orderable_type', 'orderable_id']);
        });
    }
};
