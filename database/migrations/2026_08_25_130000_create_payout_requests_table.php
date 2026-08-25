<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payout_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('method_type'); // dana|gopay|ovo|shopeepay|bank
            $table->string('account_identifier');
            $table->string('account_name');
            $table->string('status')->default('pending'); // pending|completed|rejected
            $table->text('admin_note')->nullable();
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('payout_type')->nullable()->after('phone');
            $table->string('payout_account')->nullable()->after('payout_type');
            $table->string('payout_account_name')->nullable()->after('payout_account');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payout_requests');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['payout_type', 'payout_account', 'payout_account_name']);
        });
    }
};
