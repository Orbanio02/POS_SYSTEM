<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('product_user', function (Blueprint $table) {
            // Make assigned_by nullable
            $table->unsignedBigInteger('assigned_by')->nullable()->change();

            // Drop existing foreign key if exists
            $table->dropForeign(['assigned_by']);

            // Add foreign key that sets assigned_by to NULL on delete
            $table->foreign('assigned_by')
                ->references('id')->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('product_user', function (Blueprint $table) {
            $table->dropForeign(['assigned_by']);
            $table->unsignedBigInteger('assigned_by')->nullable(false)->change();

            $table->foreign('assigned_by')
                ->references('id')->on('users')
                ->onDelete('cascade'); // or restrict, depending on your previous setup
        });
    }
};
