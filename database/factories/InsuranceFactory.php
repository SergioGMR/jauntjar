<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Insurance>
 */
class InsuranceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->company().' Insurance';

        return [
            'uuid' => fake()->uuid(),
            'name' => $name,
            'display' => $name,
            'slug' => fake()->slug(),
            'url' => fake()->url(),
        ];
    }
}
