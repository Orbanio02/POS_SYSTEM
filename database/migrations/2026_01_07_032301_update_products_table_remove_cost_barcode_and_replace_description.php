<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Replace description -> category
            $table->renameColumn('description', 'category');

            // Remove unused columns
            $table->dropColumn(['cost', 'barcode']);
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Restore removed columns
            $table->decimal('cost', 12, 2)->default(0);
            $table->string('barcode')->nullable();

            // Revert category -> description
            $table->renameColumn('category', 'description');
        });
    }
};
