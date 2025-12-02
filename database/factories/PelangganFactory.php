<?php

namespace Database\Factories;

use App\Models\Pelanggan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PelangganFactory extends Factory
{
    protected $model = Pelanggan::class;

    public function definition(): array
    {
        return [
            'nama' => $this->faker->name(),
            'nomor_telepon' => $this->faker->unique()->numerify('08##########'),
            'email' => $this->faker->unique()->safeEmail(),
            'username' => 'user_' . Str::random(5),
            'password' => bcrypt('default123'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
