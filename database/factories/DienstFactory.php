<?php

namespace Database\Factories;

use App\Models\Kapper;
use Illuminate\Database\Eloquent\Factories\Factory;

class DienstFactory extends Factory
{
    public function definition(): array
    {
        return [
            'kapper_id' => Kapper::factory(),
            'naam' => $this->faker->randomElement(['Knippen', 'Knippen + Wassen', 'Scheren', 'Baard trimmen', 'Highlights']),
            'duur_minuten' => $this->faker->randomElement([30, 45, 60, 90]),
            'prijs' => $this->faker->randomElement([1500, 2000, 2500, 3000, 3500]),
            'no_show_bedrag' => 500,
        ];
    }
}
