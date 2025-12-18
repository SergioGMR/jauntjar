<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Destination>
 */
class DestinationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'city_id' => \App\Models\City::factory(),
            'accommodation_stars' => fake()->numberBetween(1, 5),
            'accommodation_price' => fake()->randomFloat(2, 50, 500),
            'transport_type' => fake()->randomElement(['taxi', 'bus', 'metro', 'rental car']),
            'transport_price' => fake()->randomFloat(2, 10, 100),
            'arrival_date' => fake()->dateTimeBetween('now', '+1 year'),
            'duration_days' => fake()->numberBetween(1, 30),
            'displacement' => fake()->randomElement(['flight', 'train', 'bus', 'car']),
            'displacement_price' => fake()->randomFloat(2, 20, 200),
        ];
    }
}
