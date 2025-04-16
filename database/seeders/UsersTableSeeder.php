<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([

            //Admin
            [
                'name'      => 'Shin Sugiono',
                'username'  => 'shingiwoo',
                'email'     => 'admin@gmail.com',
                'password'  => Hash::make('shin4444'),
                'role'      => 'admin',
                'status'      => 'active',
            ],

            //Agen
            [
                'name'      => 'Agen Travel',
                'username'  => 'agen',
                'email'     => 'agen@gmail.com',
                'password'  => Hash::make('agen1234'),
                'role'      => 'agen',
                'status'      => 'active',
            ],

        ]);
    }
}
