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
        Schema::create('supplier_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('suppliers');
            $table->foreignId('supplier_invoice_id')->nullable()->constrained('supplier_invoices'); // Jika pembayaran untuk invoice spesifik
            $table->foreignId('booking_id')->nullable()->constrained('bookings'); // Jika ada hubungan booking
            $table->date('payment_date');
            $table->decimal('amount', 20, 2);
            $table->string('payment_method')->nullable(); // cash, transfer, etc.
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_payments');
    }
};
