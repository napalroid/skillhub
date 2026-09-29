<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_addon_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name', 100);
            $table->string('description', 255)->nullable();
            $table->decimal('price', 12, 2);
            $table->timestamps();

            $table->unique(['order_id', 'service_addon_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_addons');
    }
};
