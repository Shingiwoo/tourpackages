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
        Schema::create('package_one_days', function (Blueprint $table) {
            $table->id();
            $table->json('destinations')->nullable();
            $table->integer('agen_id');
            $table->string('name')->nullable();
            $table->integer('regency_id')->nullable();
            $table->string('participants')->nullable();
            $table->string('vehicle')->nullable();
            $table->string('price_per_person')->nullable();
            $table->string('total_price')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_one_days');
    }
};
