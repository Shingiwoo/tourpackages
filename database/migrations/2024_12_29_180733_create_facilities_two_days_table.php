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
        Schema::create('facilities_two_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id')->nullable();
            $table->unsignedBigInteger('facility_id')->nullable();

            // Add foreign key constraints
            $table->foreign('package_id')->references('id')->on('package_two_days')->onDelete('cascade')->nullable();
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities_two_days');
    }
};
