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
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->integer('regency_id');
            $table->string('name')->nullable();
            $table->string('price_wni')->nullable();
            $table->string('price_wna')->nullable();
            $table->enum('price_type',['per_person','flat'])->default('per_person');
            $table->integer('max_participants')->nullable();
            $table->string('parking_city_car')->nullable();
            $table->string('parking_mini_bus')->nullable();
            $table->string('parking_bus')->nullable();
            $table->text('ket')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
