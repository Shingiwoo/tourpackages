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
            $table->string('code_booking', 255)->nullable();
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
