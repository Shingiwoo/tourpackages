<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('provinces', function (Blueprint $table) {
            $table->id(); // ID provinsi, otomatis dari Laravel.
            $table->string('name'); // Nama provinsi.
            $table->timestamps(); // Kolom created_at dan updated_at.
        });
    }

    public function down()
    {
        Schema::dropIfExists('provinces');
    }
};
