<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RegencySeeder extends Seeder
{
    public function run()
    {
        $csvFile = fopen(base_path("database/data/regencies.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile, 1000, ",")) !== FALSE) {
            if (!$firstline) {
                DB::table('regencies')->insert([
                    "id" => $data[0],
                    "province_id" => $data[1],
                    "name" => $data[2],
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
