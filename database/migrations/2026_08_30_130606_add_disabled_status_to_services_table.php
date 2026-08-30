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
        DB::statement("ALTER TABLE services MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'disabled') DEFAULT 'pending'");
        
        DB::statement("UPDATE services SET status = 'disabled' WHERE status = 'rejected' AND id IN (SELECT service_id FROM user_notifications WHERE type = 'service_disabled')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE services SET status = 'rejected' WHERE status = 'disabled'");
        
        DB::statement("ALTER TABLE services MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }
};
