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
        Schema::create('reserve_fees', function (Blueprint $table) {
            $table->id();
            $table->integer('duration')->nullable();
            $table->string('price', 12)->nullable();
            $table->integer('min_user')->nullable();
            $table->integer('max_user')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reserve_fees');
    }
};
