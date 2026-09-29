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
        Schema::create('joki_ml_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('joki_ml_service_id')->constrained()->cascadeOnDelete();
            $table->string('current_rank', 50);
            $table->integer('current_division');
            $table->integer('current_stars');
            $table->string('target_rank', 50);
            $table->integer('target_division');
            $table->integer('target_stars');
            $table->string('ml_username', 100);
            $table->text('ml_password_encrypted');
            $table->text('hero_notes')->nullable();
            $table->text('additional_notes')->nullable();
            $table->integer('total_stars_needed');
            $table->decimal('auto_calculated_price', 12, 2)->nullable();
            $table->decimal('seller_manual_price', 12, 2)->nullable();
            $table->json('price_breakdown')->nullable();
            $table->string('current_progress')->nullable();
            $table->integer('progress_percentage')->default(0);
            $table->timestamps();
            $table->index(['order_id']);
            $table->index(['joki_ml_service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joki_ml_orders');
    }
};
