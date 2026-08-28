<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payout_requests', function (Blueprint $table) {
            // Add new columns for automatic payout
            $table->text('failure_reason')->nullable()->after('admin_note');
            $table->boolean('auto_processed')->default(true)->after('failure_reason');
        });

        // Update status enum to include new statuses
        DB::statement("ALTER TABLE payout_requests MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'failed', 'rejected') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payout_requests', function (Blueprint $table) {
            $table->dropColumn(['failure_reason', 'auto_processed']);
        });

        // Revert status enum to original
        DB::statement("ALTER TABLE payout_requests MODIFY COLUMN status ENUM('pending', 'completed', 'rejected') DEFAULT 'pending'");
    }
};
