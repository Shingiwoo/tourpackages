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
        Schema::create('package_four_days', function (Blueprint $table) {
            $table->id();
            $table->string('name_package')->nullable();
            $table->integer('regency_id')->nullable();
            $table->integer('facility_id')->nullable();
            $table->integer('agen_id')->nullable();
            $table->boolean('status')->default(true);
            $table->string('information')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_four_days');
    }
};
