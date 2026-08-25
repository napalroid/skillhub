<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->timestamp('admin_confirmed_at')->nullable()->after('verified_by');
            $table->bigInteger('admin_confirmed_by')->unsigned()->nullable()->after('admin_confirmed_at');
            $table->foreign('admin_confirmed_by')->references('id')->on('users')->nullOnDelete();
        });

        Schema::table('user_notifications', function (Blueprint $table) {
            if (! Schema::hasColumn('user_notifications', 'order_id')) {
                $table->bigInteger('order_id')->unsigned()->nullable()->after('conversation_id');
                $table->foreign('order_id')->references('id')->on('orders')->nullOnDelete();
            }
            if (! Schema::hasColumn('user_notifications', 'payment_id')) {
                $table->bigInteger('payment_id')->unsigned()->nullable()->after('order_id');
                $table->foreign('payment_id')->references('id')->on('payments')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'admin_confirmed_by')) {
                $table->dropForeign(['admin_confirmed_by']);
            }
            if (Schema::hasColumn('payments', 'admin_confirmed_at')) {
                $table->dropColumn(['admin_confirmed_at', 'admin_confirmed_by']);
            }
        });

        Schema::table('user_notifications', function (Blueprint $table) {
            if (Schema::hasColumn('user_notifications', 'payment_id')) {
                $table->dropForeign(['payment_id']);
                $table->dropColumn('payment_id');
            }
            if (Schema::hasColumn('user_notifications', 'order_id')) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            }
        });
    }
};