<?php

namespace Database\Seeders;

use App\Models\Insurance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InsuranceSeeder extends Seeder
{
    /**
     * Seed the insurances table with major travel insurance providers.
     */
    public function run(): void
    {
        $insurances = [
            // Tarjeta Sanitaria Europea (imprescindible)
            [
                'name' => 'Tarjeta Sanitaria Europea',
                'display' => 'Tarjeta Sanitaria Europea (TSE)',
                'url' => 'https://ec.europa.eu/social/main.jsp?catId=559&langId=es',
            ],
            // Seguros de viaje españoles
            [
                'name' => 'IATI Seguros',
                'display' => 'IATI Seguros',
                'url' => 'https://www.iatiseguros.com',
            ],
            [
                'name' => 'Intermundial',
                'display' => 'Intermundial',
                'url' => 'https://www.intermundial.es',
            ],
            [
                'name' => 'Chapka',
                'display' => 'Chapka',
                'url' => 'https://www.chapka.es',
            ],
            [
                'name' => 'Mondo',
                'display' => 'Mondo',
                'url' => 'https://heymondo.es',
            ],
            [
                'name' => 'Mapfre Seguros de Viaje',
                'display' => 'Mapfre',
                'url' => 'https://www.mapfre.es/seguros/seguros-de-viaje/',
            ],
            [
                'name' => 'Allianz Assistance',
                'display' => 'Allianz Assistance',
                'url' => 'https://www.allianz-assistance.es',
            ],
            [
                'name' => 'AXA Assistance',
                'display' => 'AXA Assistance',
                'url' => 'https://www.axa-assistance.es',
            ],
            // Seguros internacionales
            [
                'name' => 'World Nomads',
                'display' => 'World Nomads',
                'url' => 'https://www.worldnomads.com',
            ],
            [
                'name' => 'SafetyWing',
                'display' => 'SafetyWing',
                'url' => 'https://safetywing.com',
            ],
            [
                'name' => 'Travel Guard',
                'display' => 'Travel Guard',
                'url' => 'https://www.travelguard.com',
            ],
            // Seguros de tarjetas de crédito
            [
                'name' => 'American Express Travel Insurance',
                'display' => 'American Express',
                'url' => 'https://www.americanexpress.com/es/',
            ],
            // Asistencia en carretera y viajes
            [
                'name' => 'Europ Assistance',
                'display' => 'Europ Assistance',
                'url' => 'https://www.europ-assistance.es',
            ],
            [
                'name' => 'ERV Seguros de Viaje',
                'display' => 'ERV',
                'url' => 'https://www.erv.es',
            ],
        ];

        foreach ($insurances as $insurance) {
            Insurance::firstOrCreate(
                ['name' => $insurance['name']],
                array_merge($insurance, [
                    'uuid' => Str::uuid()->toString(),
                    'slug' => Str::slug($insurance['name']),
                ])
            );
        }
    }
}
