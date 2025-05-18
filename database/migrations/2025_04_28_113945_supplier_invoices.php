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
        Schema::create('supplier_invoices', function (Blueprint $table) {
            $table->id();
            $table->string('supplier_name');
            $table->unsignedBigInteger('booking_id')->nullable(); // Assuming booking_id might be optional
            $table->string('invoice_number')->unique();
            $table->date('date');
            $table->date('due_date')->nullable();
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->boolean('settled')->default(false);
            $table->timestamps();

            // Jika ada foreign key ke tabel bookings
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_invoices');
    }
};
