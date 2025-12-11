<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BudgetSegment>
 */
class BudgetSegmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'budget_id' => \App\Models\Budget::factory(),
            'origin_city_id' => \App\Models\City::factory(),
            'destination_city_id' => \App\Models\City::factory(),
            'stay_days' => fake()->numberBetween(1, 7),
            'order' => 0,
        ];
    }
}
