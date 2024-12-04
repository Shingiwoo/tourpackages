<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. 1
     */
    public function up()
    {
        Schema::table('package_destinations', function (Blueprint $table) {
            //drop columns first
            $table->dropForeign(['package_id']);
            $table->dropForeign(['destination_id']);
            // Add columns if not exists
            $table->unsignedBigInteger('package_id')->after('id')->nullable();
            $table->unsignedBigInteger('destination_id')->after('package_id')->nullable();

            // Add foreign key constraints
            $table->foreign('package_id')->references('id')->on('package_one_days')->onDelete('cascade')->nullable();
            $table->foreign('destination_id')->references('id')->on('destinations')->onDelete('cascade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('package_destinations', function (Blueprint $table) {
            $table->dropForeign(['package_id']);
            $table->dropForeign(['destination_id']);
            $table->dropColumn('package_id');
            $table->dropColumn('destination_id');
        });
    }
};
