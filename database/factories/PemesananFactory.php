<?php

namespace Database\Factories;

use App\Models\Pemesanan;
use App\Models\Pelanggan;
use App\Models\Meja;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PemesananFactory extends Factory
{
    protected $model = Pemesanan::class;

    public function definition(): array
    {
        return [
            'order_id' => strtoupper(Str::random(6)),
            'tanggal' => $this->faker->dateTimeBetween('now', '+1 week')->format('Y-m-d'),
            'total_harga' => 20000,
            'status_pembayaran' => 'sudah_dibayar',
            'proses_pemesanan' => 'selesai',
            'metode_pembayaran' => 'offline',
            'id_meja' => Meja::factory(),
            'id_pelanggan' => Pelanggan::factory(),
            'reschedule_count' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
