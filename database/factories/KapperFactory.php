<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class KapperFactory extends Factory
{
    public function definition(): array
    {
        $naam = $this->faker->company();
        return [
            'user_id' => User::factory()->state(['role' => 'kapper']),
            'salon_naam' => $naam,
            'slug' => Str::slug($naam) . '-' . $this->faker->unique()->numberBetween(1, 999),
            'adres' => $this->faker->streetAddress(),
            'stad' => $this->faker->city(),
            'telefoon' => $this->faker->phoneNumber(),
            'bio' => $this->faker->paragraph(),
            'abonnement_status' => 'actief',
            'actief' => true,
        ];
    }
}
