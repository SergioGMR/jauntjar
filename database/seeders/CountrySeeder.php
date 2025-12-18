<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CountrySeeder extends Seeder
{
    /**
     * Seed the countries table with European countries.
     */
    public function run(): void
    {
        $countries = [
            // España
            [
                'name' => 'España',
                'display' => 'España',
                'code' => 'ES',
                'currency' => 'EUR',
                'language' => 'Español',
                'roaming' => 'UE',
                'visa' => 'No requerido (UE)',
            ],
            // Principales países de Europa
            [
                'name' => 'Alemania',
                'display' => 'Alemania',
                'code' => 'DE',
                'currency' => 'EUR',
                'language' => 'Alemán',
                'roaming' => 'UE',
                'visa' => 'No requerido (UE)',
            ],
            [
                'name' => 'Francia',
                'display' => 'Francia',
                'code' => 'FR',
                'currency' => 'EUR',
                'language' => 'Francés',
                'roaming' => 'UE',
                'visa' => 'No requerido (UE)',
            ],
            [
                'name' => 'Italia',
                'display' => 'Italia',
                'code' => 'IT',
                'currency' => 'EUR',
                'language' => 'Italiano',
                'roaming' => 'UE',
                'visa' => 'No requerido (UE)',
            ],
            [
                'name' => 'Portugal',
                'display' => 'Portugal',
                'code' => 'PT',
                'currency' => 'EUR',
                'language' => 'Portugués',
                'roaming' => 'UE',
                'visa' => 'No requerido (UE)',
            ],
            [
                'name' => 'Reino Unido',
                'display' => 'Reino Unido',
                'code' => 'GB',
                'currency' => 'GBP',
                'language' => 'Inglés',
                'roaming' => 'No UE',
                'visa' => 'No requerido (hasta 6 meses)',
            ],
            [
                'name' => 'Países Bajos',
                'display' => 'Países Bajos',
                'code' => 'NL',
                'currency' => 'EUR',
                'language' => 'Neerlandés',
                'roaming' => 'UE',
                'visa' => 'No requerido (UE)',
            ],
            [
                'name' => 'Bélgica',
                'display' => 'Bélgica',
                'code' => 'BE',
                'currency' => 'EUR',
                'language' => 'Neerlandés, Francés, Alemán',
                'roaming' => 'UE',
                'visa' => 'No requerido (UE)',
            ],
            [
                'name' => 'Austria',
                'display' => 'Austria',
                'code' => 'AT',
                'currency' => 'EUR',
                'language' => 'Alemán',
                'roaming' => 'UE',
                'visa' => 'No requerido (UE)',
            ],
            [
                'name' => 'Suiza',
                'display' => 'Suiza',
                'code' => 'CH',
                'currency' => 'CHF',
                'language' => 'Alemán, Francés, Italiano, Romanche',
                'roaming' => 'No UE (tarifas especiales)',
                'visa' => 'No requerido (Schengen)',
            ],
            [
                'name' => 'Grecia',
                'display' => 'Grecia',
                'code' => 'GR',
                'currency' => 'EUR',
                'language' => 'Griego',
                'roaming' => 'UE',
                'visa' => 'No requerido (UE)',
            ],
            [
                'name' => 'Irlanda',
                'display' => 'Irlanda',
                'code' => 'IE',
                'currency' => 'EUR',
                'language' => 'Inglés, Irlandés',
                'roaming' => 'UE',
                'visa' => 'No requerido (UE)',
            ],
            [
                'name' => 'Polonia',
                'display' => 'Polonia',
                'code' => 'PL',
                'currency' => 'PLN',
                'language' => 'Polaco',
                'roaming' => 'UE',
                'visa' => 'No requerido (UE)',
            ],
            [
                'name' => 'Suecia',
                'display' => 'Suecia',
                'code' => 'SE',
                'currency' => 'SEK',
                'language' => 'Sueco',
                'roaming' => 'UE',
                'visa' => 'No requerido (UE)',
            ],
            [
                'name' => 'Noruega',
                'display' => 'Noruega',
                'code' => 'NO',
                'currency' => 'NOK',
                'language' => 'Noruego',
                'roaming' => 'EEE',
                'visa' => 'No requerido (Schengen)',
            ],
            [
                'name' => 'Dinamarca',
                'display' => 'Dinamarca',
                'code' => 'DK',
                'currency' => 'DKK',
                'language' => 'Danés',
                'roaming' => 'UE',
                'visa' => 'No requerido (UE)',
            ],
            [
                'name' => 'Finlandia',
                'display' => 'Finlandia',
                'code' => 'FI',
                'currency' => 'EUR',
                'language' => 'Finés, Sueco',
                'roaming' => 'UE',
                'visa' => 'No requerido (UE)',
            ],
            [
                'name' => 'Chequia',
                'display' => 'República Checa',
                'code' => 'CZ',
                'currency' => 'CZK',
                'language' => 'Checo',
                'roaming' => 'UE',
                'visa' => 'No requerido (UE)',
            ],
            [
                'name' => 'Hungría',
                'display' => 'Hungría',
                'code' => 'HU',
                'currency' => 'HUF',
                'language' => 'Húngaro',
                'roaming' => 'UE',
                'visa' => 'No requerido (UE)',
            ],
            [
                'name' => 'Croacia',
                'display' => 'Croacia',
                'code' => 'HR',
                'currency' => 'EUR',
                'language' => 'Croata',
                'roaming' => 'UE',
                'visa' => 'No requerido (UE)',
            ],
            [
                'name' => 'Islandia',
                'display' => 'Islandia',
                'code' => 'IS',
                'currency' => 'ISK',
                'language' => 'Islandés',
                'roaming' => 'EEE',
                'visa' => 'No requerido (Schengen)',
            ],
        ];

        foreach ($countries as $country) {
            Country::firstOrCreate(
                ['code' => $country['code']],
                array_merge($country, [
                    'uuid' => Str::uuid()->toString(),
                    'slug' => Str::slug($country['name']),
                    'pibpc' => 0,
                    'womens_rights' => 0,
                    'lgtb_rights' => 0,
                ])
            );
        }
    }
}
