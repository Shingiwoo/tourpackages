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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->integer('booking_list_id')->nullable();
            $table->string('code_booking', 255)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->integer('total_user')->nullable();
            $table->string('price_person')->nullable();
            $table->string('total_price')->nullable();
            $table->string('down_paymet')->nullable();
            $table->string('remaining_costs')->nullable();
            $table->enum('status', ['pending','booked','ontrip','paid','finished'])->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
