<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('penggunas')->insert([
            [
                'username' => 'ownerku',
                'email' => 'owner@gmail.com',
                'password' => Hash::make('password123'),
                'jenis_pengguna' => 'owner',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'adminku',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password123'),
                'jenis_pengguna' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
