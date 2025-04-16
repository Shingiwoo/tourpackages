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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->enum('type',['City Car', 'Mini Bus', 'Bus', 'Shuttle Dieng'])->default('City Car');
            $table->integer('capacity_min')->nullable();
            $table->integer('capacity_max')->nullable();
            $table->string('price')->nullable();
            $table->foreignId('regency_id')->constrained('regencies')->onDelete('cascade');
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
