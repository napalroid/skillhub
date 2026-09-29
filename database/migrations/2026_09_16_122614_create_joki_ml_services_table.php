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
        Schema::create('joki_ml_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->enum('pricing_mode', ['auto', 'manual'])->default('auto');
            $table->json('price_per_star_config')->nullable();
            $table->json('rank_range')->nullable();
            $table->text('special_notes')->nullable();
            $table->integer('estimated_completion_days')->nullable();
            $table->timestamps();
            $table->index(['service_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('joki_ml_services');
    }
};
