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
        Schema::create('service_types', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('icon')->nullable();
            $table->text('description')->nullable();
            $table->string('service_table')->nullable();
            $table->string('order_table')->nullable();
            $table->string('service_model')->nullable();
            $table->string('order_model')->nullable();
            $table->boolean('has_custom_pricing')->default(false);
            $table->boolean('requires_manual_approval')->default(false);
            $table->boolean('hidden_from_listing')->default(false);
            $table->boolean('enable_time_slots')->default(false);
            $table->json('seller_fields')->nullable();
            $table->json('buyer_fields')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_types');
    }
};
