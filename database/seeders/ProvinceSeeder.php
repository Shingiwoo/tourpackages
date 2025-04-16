<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProvinceSeeder extends Seeder
{
    public function run()
    {
        $csvFile = fopen(base_path("database/data/provinces.csv"), "r");
        $firstline = true;
        while (($data = fgetcsv($csvFile, 1000, ",")) !== FALSE) {
            if (!$firstline) {
                DB::table('provinces')->insert([
                    "id" => $data[0],
                    "name" => $data[1],
                ]);
            }
            $firstline = false;
        }
        fclose($csvFile);
    }
}
