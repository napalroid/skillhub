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
        Schema::create('order_price_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('changed_by')->constrained('users')->cascadeOnDelete();
            $table->decimal('old_price', 10, 2)->nullable();
            $table->decimal('new_price', 10, 2);
            $table->text('note')->nullable();
            $table->timestamps();
            
            $table->index(['order_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_price_histories');
    }
};
