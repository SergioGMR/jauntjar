<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Budget>
 */
class BudgetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'airline_id' => \App\Models\Airline::factory(),
            'insurance_id' => \App\Models\Insurance::factory(),
            'name' => fake()->words(2, true),
            'display' => fake()->sentence(3),
            'slug' => fake()->slug(),
            'flight_ticket_price' => fake()->numberBetween(100, 1000),
            'insurance_price' => fake()->numberBetween(20, 200),
            'total_price' => fake()->numberBetween(200, 1500),
        ];
    }
}
