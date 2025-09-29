<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    protected $model = Country::class;

    public function definition(): array
    {
        $name = fake()->country();
        
        return [
            'uuid' => fake()->uuid(),
            'name' => $name,
            'display' => $name,
            'slug' => fake()->slug(),
            'code' => fake()->countryCode(),
            'currency' => fake()->currencyCode(),
            'pibpc' => fake()->numberBetween(5000, 80000),
            'womens_rights' => fake()->numberBetween(1, 10),
            'lgtb_rights' => fake()->numberBetween(1, 10),
            'visa' => fake()->randomElement(['Sí', 'No', 'eVisa']),
            'language' => fake()->languageCode(),
            'roaming' => fake()->randomElement(['Incluido', 'N/A', 'Extra']),
        ];
    }
}
