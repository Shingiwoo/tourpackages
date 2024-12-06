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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->integer('regency_id')->nullable();
            $table->string('name')->nullable();
            $table->enum('type',['Two Star','Three Star', 'Four Star', 'Five Star', 'Villa', 'Homestay', 'Cottage', 'Cabin','Guesthouse', 'Without Accomodation'])->default('Three Star');
            $table->string('price')->nullable();
            $table->string('extrabed_price')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
