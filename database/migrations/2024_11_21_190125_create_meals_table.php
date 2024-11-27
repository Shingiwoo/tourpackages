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
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->enum('type',['1D', '2D', '3D', '4D', '5D'])->default('1D');
            $table->enum('duration',['1', '2', '3', '4', '5'])->default('1');
            $table->string('num_meals');
            $table->integer('regency_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
