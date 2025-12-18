<?php

namespace Database\Seeders;

use App\Models\Airline;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AirlineSeeder extends Seeder
{
    /**
     * Seed the airlines table with major European airlines.
     */
    public function run(): void
    {
        $airlines = [
            // Aerolíneas españolas
            [
                'name' => 'Iberia',
                'display' => 'Iberia',
                'is_low_cost' => false,
            ],
            [
                'name' => 'Vueling',
                'display' => 'Vueling',
                'is_low_cost' => true,
            ],
            [
                'name' => 'Air Europa',
                'display' => 'Air Europa',
                'is_low_cost' => false,
            ],
            [
                'name' => 'Binter Canarias',
                'display' => 'Binter Canarias',
                'is_low_cost' => false,
            ],
            [
                'name' => 'Iberia Express',
                'display' => 'Iberia Express',
                'is_low_cost' => true,
            ],
            [
                'name' => 'Air Nostrum',
                'display' => 'Air Nostrum',
                'is_low_cost' => false,
            ],
            [
                'name' => 'Canaryfly',
                'display' => 'Canaryfly',
                'is_low_cost' => true,
            ],
            // Aerolíneas europeas low cost
            [
                'name' => 'easyJet',
                'display' => 'easyJet',
                'is_low_cost' => true,
            ],
            [
                'name' => 'Wizz Air',
                'display' => 'Wizz Air',
                'is_low_cost' => true,
            ],
            [
                'name' => 'Eurowings',
                'display' => 'Eurowings',
                'is_low_cost' => true,
            ],
            [
                'name' => 'Transavia',
                'display' => 'Transavia',
                'is_low_cost' => true,
            ],
            [
                'name' => 'Norwegian Air Shuttle',
                'display' => 'Norwegian',
                'is_low_cost' => true,
            ],
            [
                'name' => 'Volotea',
                'display' => 'Volotea',
                'is_low_cost' => true,
            ],
            // Aerolíneas tradicionales europeas
            [
                'name' => 'Lufthansa',
                'display' => 'Lufthansa',
                'is_low_cost' => false,
            ],
            [
                'name' => 'Air France',
                'display' => 'Air France',
                'is_low_cost' => false,
            ],
            [
                'name' => 'British Airways',
                'display' => 'British Airways',
                'is_low_cost' => false,
            ],
            [
                'name' => 'KLM Royal Dutch Airlines',
                'display' => 'KLM',
                'is_low_cost' => false,
            ],
            [
                'name' => 'Alitalia',
                'display' => 'ITA Airways',
                'is_low_cost' => false,
            ],
            [
                'name' => 'TAP Air Portugal',
                'display' => 'TAP Portugal',
                'is_low_cost' => false,
            ],
            [
                'name' => 'Swiss International Air Lines',
                'display' => 'Swiss',
                'is_low_cost' => false,
            ],
            [
                'name' => 'Austrian Airlines',
                'display' => 'Austrian',
                'is_low_cost' => false,
            ],
            [
                'name' => 'Brussels Airlines',
                'display' => 'Brussels Airlines',
                'is_low_cost' => false,
            ],
            [
                'name' => 'SAS Scandinavian Airlines',
                'display' => 'SAS',
                'is_low_cost' => false,
            ],
            [
                'name' => 'Finnair',
                'display' => 'Finnair',
                'is_low_cost' => false,
            ],
            [
                'name' => 'Aer Lingus',
                'display' => 'Aer Lingus',
                'is_low_cost' => false,
            ],
            [
                'name' => 'Icelandair',
                'display' => 'Icelandair',
                'is_low_cost' => false,
            ],
            [
                'name' => 'LOT Polish Airlines',
                'display' => 'LOT',
                'is_low_cost' => false,
            ],
            [
                'name' => 'Aegean Airlines',
                'display' => 'Aegean Airlines',
                'is_low_cost' => false,
            ],
            [
                'name' => 'Croatia Airlines',
                'display' => 'Croatia Airlines',
                'is_low_cost' => false,
            ],
        ];

        foreach ($airlines as $airline) {
            Airline::firstOrCreate(
                ['name' => $airline['name']],
                array_merge($airline, [
                    'uuid' => Str::uuid()->toString(),
                    'slug' => Str::slug($airline['name']),
                    'logo' => '',
                ])
            );
        }
    }
}
