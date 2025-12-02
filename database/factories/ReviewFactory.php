<?php

namespace Database\Factories;

use App\Models\Review;
use App\Models\Pemesanan;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition()
    {
        return [
            'pemesanan_id' => Pemesanan::factory(),
            'nilai_rating' => $this->faker->numberBetween(1, 5),
            'komentar' => $this->faker->sentence(),
        ];
    }
}
