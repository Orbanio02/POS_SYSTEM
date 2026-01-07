<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Core product info
            $table->string('name');
            $table->string('sku')->nullable()->unique();
            $table->text('description')->nullable();

            // Pricing
            $table->decimal('cost', 12, 2)->default(0);
            $table->decimal('price', 12, 2)->default(0);

            // Inventory (FIXED)
            $table->integer('stock_quantity')->default(0);
            $table->integer('low_stock_threshold')->nullable();

            // Optional metadata
            $table->string('barcode')->nullable();
            $table->string('image')->nullable();

            // Status
            $table->boolean('is_active')->default(true);

            // Soft deletes & timestamps
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
