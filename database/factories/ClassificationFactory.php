<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Classification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classification>
 */
class ClassificationFactory extends Factory
{
    protected $model = Classification::class;

    public function definition(): array
    {
        $cost = fake()->numberBetween(30, 100);
        $culture = fake()->numberBetween(30, 100);
        $weather = fake()->numberBetween(30, 100);
        $food = fake()->numberBetween(30, 100);

        return [
            'uuid' => fake()->uuid(),
            'city_id' => City::factory(),
            'cost' => $cost,
            'culture' => $culture,
            'weather' => $weather,
            'food' => $food,
            'total' => round(($cost + $culture + $weather + $food) / 4),
        ];
    }
}
