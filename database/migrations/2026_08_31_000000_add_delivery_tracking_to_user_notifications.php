<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_notifications', function (Blueprint $table) {
            $table->timestamp('delivered_at')->nullable()->after('is_read');
            $table->timestamp('ack_received_at')->nullable()->after('delivered_at');
            $table->unsignedTinyInteger('retry_count')->default(0)->after('ack_received_at');
            $table->timestamp('last_retry_at')->nullable()->after('retry_count');
        });
    }

    public function down(): void
    {
        Schema::table('user_notifications', function (Blueprint $table) {
            $table->dropColumn(['delivered_at', 'ack_received_at', 'retry_count', 'last_retry_at']);
        });
    }
};
