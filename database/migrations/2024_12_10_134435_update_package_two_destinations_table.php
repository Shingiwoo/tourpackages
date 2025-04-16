<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('package_two_destinations', function (Blueprint $table) {

            // Modify the columns
            $table->unsignedBigInteger('package_id')->nullable()->change();
            $table->unsignedBigInteger('destination_id')->nullable()->change();

            // Add foreign key constraints
            $table->foreign('package_id')->references('id')->on('package_two_days')->onDelete('cascade')->nullable();
            $table->foreign('destination_id')->references('id')->on('destinations')->onDelete('cascade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('package_two_destinations', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['package_id']);
            $table->dropForeign(['destination_id']);

            // Remove nullable and foreign key constraints
            $table->dropColumn('package_id');
            $table->dropColumn('destination_id');
        });
    }
};
