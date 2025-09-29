<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\City>
 */
class CityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => fake()->uuid(),
            'country_id' => \App\Models\Country::factory(),
            'name' => fake()->city(),
            'display' => fake()->city(),
            'slug' => fake()->slug(),
            'stops' => fake()->numberBetween(0, 5),
            'visited' => false,
            'coordinates' => [
                'lat' => fake()->latitude(),
                'lng' => fake()->longitude(),
            ],
        ];
    }

    public function visited(): static
    {
        return $this->state(fn (array $attributes) => [
            'visited' => true,
        ]);
    }
}
