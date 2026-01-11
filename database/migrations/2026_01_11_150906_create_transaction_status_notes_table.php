<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaction_status_notes', function (Blueprint $table) {
            $table->id();

            // payment_id in your system (kept as transaction_id by design)
            $table->unsignedBigInteger('transaction_id');

            $table->string('status'); // pending | approved | rejected
            $table->text('note');
            $table->unsignedBigInteger('created_by');

            $table->timestamps();

            $table->foreign('transaction_id')
                ->references('id')
                ->on('payments')
                ->onDelete('cascade');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_status_notes');
    }
};
