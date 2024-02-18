<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        \App\Models\User::factory()->create([
            "username" => "Galih",
            "email" => "galsans@gmail.com",
            "password" => Hash::make('password'),
            "no_ktp" => "1293938288",
            "date_birth" => "2007-05-01",
            "phone" => "088919288273",
            "description" => "lorem ipsum dolor sit emet",
            "role" => "admin",
        ]);

        \App\Models\User::factory()->create([
            "username" => "Darma",
            "email" => "pengguna@gmail.com",
            "password" => Hash::make('password'),
            "no_ktp" => "1288399203",
            "date_birth" => "2007-01-08",
            "phone" => "081288377812",
            "description" => "lorem ipsum dolor sit emet",
            "role" => "pengguna",
        ]);
    }
}
