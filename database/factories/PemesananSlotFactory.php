<?php

namespace Database\Factories;

use App\Models\PemesananSlot;
use App\Models\Pemesanan;
use Illuminate\Database\Eloquent\Factories\Factory;

class PemesananSlotFactory extends Factory
{
    protected $model = PemesananSlot::class;

    public function definition(): array
    {
        return [
            'pemesanan_id' => Pemesanan::factory(),
            'jam_mulai' => '09:00',
            'jam_akhir' => '10:00',
            'harga' => 20000,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
