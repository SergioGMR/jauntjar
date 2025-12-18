<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    /**
     * Seed the cities table with Spanish and European cities.
     */
    public function run(): void
    {
        // Principales ciudades de España
        $spanishCities = [
            ['name' => 'Madrid', 'coordinates' => '40.4168,-3.7038'],
            ['name' => 'Barcelona', 'coordinates' => '41.3851,2.1734'],
            ['name' => 'Valencia', 'coordinates' => '39.4699,-0.3763'],
            ['name' => 'Sevilla', 'coordinates' => '37.3891,-5.9845'],
            ['name' => 'Bilbao', 'coordinates' => '43.2630,-2.9350'],
            ['name' => 'Málaga', 'coordinates' => '36.7213,-4.4214'],
            ['name' => 'Zaragoza', 'coordinates' => '41.6488,-0.8891'],
            ['name' => 'Las Palmas de Gran Canaria', 'coordinates' => '28.1235,-15.4363'],
            ['name' => 'Santa Cruz de Tenerife', 'coordinates' => '28.4636,-16.2518'],
            ['name' => 'Palma de Mallorca', 'coordinates' => '39.5696,2.6502'],
            ['name' => 'Granada', 'coordinates' => '37.1773,-3.5986'],
            ['name' => 'Alicante', 'coordinates' => '38.3452,-0.4810'],
            ['name' => 'San Sebastián', 'coordinates' => '43.3183,-1.9812'],
            ['name' => 'A Coruña', 'coordinates' => '43.3623,-8.4115'],
            ['name' => 'Santander', 'coordinates' => '43.4623,-3.8100'],
            ['name' => 'Córdoba', 'coordinates' => '37.8882,-4.7794'],
            ['name' => 'Murcia', 'coordinates' => '37.9922,-1.1307'],
            ['name' => 'Valladolid', 'coordinates' => '41.6523,-4.7245'],
            ['name' => 'Vigo', 'coordinates' => '42.2406,-8.7207'],
            ['name' => 'Gijón', 'coordinates' => '43.5322,-5.6611'],
            ['name' => 'Oviedo', 'coordinates' => '43.3614,-5.8493'],
            ['name' => 'Pamplona', 'coordinates' => '42.8125,-1.6458'],
            ['name' => 'Toledo', 'coordinates' => '39.8628,-4.0273'],
            ['name' => 'Salamanca', 'coordinates' => '40.9701,-5.6635'],
            ['name' => 'Ibiza', 'coordinates' => '38.9067,1.4206'],
        ];

        // Principales ciudades de Europa
        $europeanCities = [
            // Alemania
            ['name' => 'Berlín', 'country_code' => 'DE', 'coordinates' => '52.5200,13.4050'],
            ['name' => 'Múnich', 'country_code' => 'DE', 'coordinates' => '48.1351,11.5820'],
            ['name' => 'Fráncfort', 'country_code' => 'DE', 'coordinates' => '50.1109,8.6821'],
            ['name' => 'Hamburgo', 'country_code' => 'DE', 'coordinates' => '53.5511,9.9937'],
            // Francia
            ['name' => 'París', 'country_code' => 'FR', 'coordinates' => '48.8566,2.3522'],
            ['name' => 'Marsella', 'country_code' => 'FR', 'coordinates' => '43.2965,5.3698'],
            ['name' => 'Lyon', 'country_code' => 'FR', 'coordinates' => '45.7640,4.8357'],
            ['name' => 'Niza', 'country_code' => 'FR', 'coordinates' => '43.7102,7.2620'],
            ['name' => 'Burdeos', 'country_code' => 'FR', 'coordinates' => '44.8378,-0.5792'],
            // Italia
            ['name' => 'Roma', 'country_code' => 'IT', 'coordinates' => '41.9028,12.4964'],
            ['name' => 'Milán', 'country_code' => 'IT', 'coordinates' => '45.4642,9.1900'],
            ['name' => 'Florencia', 'country_code' => 'IT', 'coordinates' => '43.7696,11.2558'],
            ['name' => 'Venecia', 'country_code' => 'IT', 'coordinates' => '45.4408,12.3155'],
            ['name' => 'Nápoles', 'country_code' => 'IT', 'coordinates' => '40.8518,14.2681'],
            // Portugal
            ['name' => 'Lisboa', 'country_code' => 'PT', 'coordinates' => '38.7223,-9.1393'],
            ['name' => 'Oporto', 'country_code' => 'PT', 'coordinates' => '41.1579,-8.6291'],
            ['name' => 'Faro', 'country_code' => 'PT', 'coordinates' => '37.0194,-7.9322'],
            // Reino Unido
            ['name' => 'Londres', 'country_code' => 'GB', 'coordinates' => '51.5074,-0.1278'],
            ['name' => 'Edimburgo', 'country_code' => 'GB', 'coordinates' => '55.9533,-3.1883'],
            ['name' => 'Mánchester', 'country_code' => 'GB', 'coordinates' => '53.4808,-2.2426'],
            ['name' => 'Liverpool', 'country_code' => 'GB', 'coordinates' => '53.4084,-2.9916'],
            // Países Bajos
            ['name' => 'Ámsterdam', 'country_code' => 'NL', 'coordinates' => '52.3676,4.9041'],
            ['name' => 'Róterdam', 'country_code' => 'NL', 'coordinates' => '51.9244,4.4777'],
            // Bélgica
            ['name' => 'Bruselas', 'country_code' => 'BE', 'coordinates' => '50.8503,4.3517'],
            ['name' => 'Brujas', 'country_code' => 'BE', 'coordinates' => '51.2093,3.2247'],
            ['name' => 'Amberes', 'country_code' => 'BE', 'coordinates' => '51.2194,4.4025'],
            // Austria
            ['name' => 'Viena', 'country_code' => 'AT', 'coordinates' => '48.2082,16.3738'],
            ['name' => 'Salzburgo', 'country_code' => 'AT', 'coordinates' => '47.8095,13.0550'],
            // Suiza
            ['name' => 'Zúrich', 'country_code' => 'CH', 'coordinates' => '47.3769,8.5417'],
            ['name' => 'Ginebra', 'country_code' => 'CH', 'coordinates' => '46.2044,6.1432'],
            ['name' => 'Berna', 'country_code' => 'CH', 'coordinates' => '46.9480,7.4474'],
            // Grecia
            ['name' => 'Atenas', 'country_code' => 'GR', 'coordinates' => '37.9838,23.7275'],
            ['name' => 'Salónica', 'country_code' => 'GR', 'coordinates' => '40.6401,22.9444'],
            ['name' => 'Santorini', 'country_code' => 'GR', 'coordinates' => '36.3932,25.4615'],
            // Irlanda
            ['name' => 'Dublín', 'country_code' => 'IE', 'coordinates' => '53.3498,-6.2603'],
            ['name' => 'Cork', 'country_code' => 'IE', 'coordinates' => '51.8985,-8.4756'],
            // Polonia
            ['name' => 'Varsovia', 'country_code' => 'PL', 'coordinates' => '52.2297,21.0122'],
            ['name' => 'Cracovia', 'country_code' => 'PL', 'coordinates' => '50.0647,19.9450'],
            // Suecia
            ['name' => 'Estocolmo', 'country_code' => 'SE', 'coordinates' => '59.3293,18.0686'],
            ['name' => 'Gotemburgo', 'country_code' => 'SE', 'coordinates' => '57.7089,11.9746'],
            // Noruega
            ['name' => 'Oslo', 'country_code' => 'NO', 'coordinates' => '59.9139,10.7522'],
            ['name' => 'Bergen', 'country_code' => 'NO', 'coordinates' => '60.3913,5.3221'],
            // Dinamarca
            ['name' => 'Copenhague', 'country_code' => 'DK', 'coordinates' => '55.6761,12.5683'],
            // Finlandia
            ['name' => 'Helsinki', 'country_code' => 'FI', 'coordinates' => '60.1699,24.9384'],
            // Chequia
            ['name' => 'Praga', 'country_code' => 'CZ', 'coordinates' => '50.0755,14.4378'],
            // Hungría
            ['name' => 'Budapest', 'country_code' => 'HU', 'coordinates' => '47.4979,19.0402'],
            // Croacia
            ['name' => 'Zagreb', 'country_code' => 'HR', 'coordinates' => '45.8150,15.9819'],
            ['name' => 'Dubrovnik', 'country_code' => 'HR', 'coordinates' => '42.6507,18.0944'],
            ['name' => 'Split', 'country_code' => 'HR', 'coordinates' => '43.5081,16.4402'],
            // Islandia
            ['name' => 'Reikiavik', 'country_code' => 'IS', 'coordinates' => '64.1466,-21.9426'],
        ];

        // Insertar ciudades españolas
        $spain = Country::where('code', 'ES')->first();
        if ($spain) {
            foreach ($spanishCities as $city) {
                City::firstOrCreate(
                    ['name' => $city['name'], 'country_id' => $spain->id],
                    [
                        'uuid' => Str::uuid()->toString(),
                        'display' => $city['name'],
                        'slug' => Str::slug($city['name']),
                        'coordinates' => $city['coordinates'],
                        'visited' => false,
                    ]
                );
            }
        }

        // Insertar ciudades europeas
        foreach ($europeanCities as $city) {
            $country = Country::where('code', $city['country_code'])->first();
            if ($country) {
                City::firstOrCreate(
                    ['name' => $city['name'], 'country_id' => $country->id],
                    [
                        'uuid' => Str::uuid()->toString(),
                        'display' => $city['name'],
                        'slug' => Str::slug($city['name']),
                        'coordinates' => $city['coordinates'],
                        'visited' => false,
                    ]
                );
            }
        }
    }
}
