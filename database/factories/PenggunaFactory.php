<?php

namespace Database\Factories;

use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class PenggunaFactory extends Factory
{
    protected $model = Pengguna::class;

    public function definition(): array
    {
        return [
            'username' => 'adminku',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
            'jenis_pengguna' => 'admin',
        ];
    }
}
