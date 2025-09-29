<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Airline>
 */
class AirlineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company();

        return [
            'uuid' => fake()->uuid(),
            'name' => $name,
            'display' => $name,
            'slug' => fake()->slug(),
            'logo' => fake()->imageUrl(200, 100, 'airline'),
            'is_low_cost' => fake()->boolean(),
        ];
    }
}
