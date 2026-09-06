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
        Schema::create('time_slot_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('old_time_slot_id')->nullable()->constrained('service_time_slots')->nullOnDelete();
            $table->foreignId('new_time_slot_id')->constrained('service_time_slots')->cascadeOnDelete();
            $table->enum('changed_by', ['seller', 'system'])->default('seller');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_slot_history');
    }
};
