<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('regencies', function (Blueprint $table) {
            $table->id(); // ID kabupaten/kota.
            $table->unsignedBigInteger('province_id'); // Foreign key ke tabel provinces.
            $table->string('name'); // Nama kabupaten/kota.
            $table->timestamps();

            // Relasi dengan tabel provinces
            $table->foreign('province_id')->references('id')->on('provinces')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('regencies');
    }
};
