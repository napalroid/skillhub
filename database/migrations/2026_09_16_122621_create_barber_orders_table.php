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
        Schema::create('barber_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('barber_service_id')->constrained()->cascadeOnDelete();
            $table->string('selected_haircut_type', 100);
            $table->json('selected_additional_services')->nullable();
            $table->text('special_requests')->nullable();
            $table->foreignId('time_slot_id')->nullable()->constrained('service_time_slots')->nullOnDelete();
            $table->timestamps();
            $table->index(['order_id']);
            $table->index(['barber_service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barber_orders');
    }
};
