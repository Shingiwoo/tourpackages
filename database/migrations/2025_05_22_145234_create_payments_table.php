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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['dp', 'pelunasan']);
            $table->integer('dp_installment')->nullable();
            $table->string('ammount', 25)->nullable();
            $table->enum('status', ['waiting', 'terbayar', 'cancel'])->default('waiting');
            $table->timestamp('payment_due_date')->nullable();
            $table->enum('method', ['tunai', 'transfer', 'virtual_account']);
            $table->string('proof_of_transfer')->nullable();
            $table->timestamp('payment_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
