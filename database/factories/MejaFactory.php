<?php

namespace Database\Factories;

use App\Models\Meja;
use Illuminate\Database\Eloquent\Factories\Factory;

class MejaFactory extends Factory
{
    protected $model = Meja::class;
    public function definition(): array
    {
        return [
            'tipe_meja' => $this->faker->randomElement(['Reguler', 'VIP']),
            'harga_sewa' => $this->faker->numberBetween(20000, 50000),
            'deskripsi' => $this->faker->sentence(),
            'foto' => 'default.jpg',
            'status' => 'aktif',
        ];
    }
}
