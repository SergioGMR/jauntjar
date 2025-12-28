<?php

use App\Livewire\Components\MapsGoogle;
use App\Models\City;
use App\Models\Classification;
use App\Models\Country;
use Livewire\Livewire;

it('can render component', function () {
    Livewire::test(MapsGoogle::class)
        ->assertOk();
});

it('has default properties', function () {
    $component = Livewire::test(MapsGoogle::class);

    expect($component->get('mapType'))->toBe('hybrid');
    expect($component->get('zoomLevel'))->toBe(5);
    expect($component->get('fitToBounds'))->toBeTrue();
    expect($component->get('centerToBoundsCenter'))->toBeTrue();
    expect($component->get('centerPoint'))->toBe(['lat' => 28.2925418, 'long' => -15.9515938]);
});

it('loads markers from classifications on mount', function () {
    $country = Country::factory()->create();
    $city = City::factory()->create([
        'country_id' => $country->id,
        'display' => 'Test City',
        'coordinates' => ['lat' => 40.7128, 'lng' => -74.0060],
    ]);
    Classification::factory()->create([
        'city_id' => $city->id,
        'total' => 85,
    ]);

    $component = Livewire::test(MapsGoogle::class);
    $markers = $component->get('markers');

    expect($markers)->toHaveCount(1);
    expect($markers[0])->toHaveKey('lat');
    expect($markers[0])->toHaveKey('long');
    expect($markers[0])->toHaveKey('title');
    expect($markers[0])->toHaveKey('info');
    expect($markers[0]['lat'])->toBe(40.7128);
    expect($markers[0]['long'])->toBe(-74.0060);
    expect($markers[0]['title'])->toBe('Test City');
    expect($markers[0]['info'])->toBe(85);
});

it('handles multiple classifications', function () {
    $country = Country::factory()->create();

    $city1 = City::factory()->create([
        'country_id' => $country->id,
        'display' => 'City One',
        'coordinates' => ['lat' => 40.7128, 'lng' => -74.0060],
    ]);
    $city2 = City::factory()->create([
        'country_id' => $country->id,
        'display' => 'City Two',
        'coordinates' => ['lat' => 51.5074, 'lng' => -0.1278],
    ]);

    Classification::factory()->create(['city_id' => $city1->id, 'total' => 90]);
    Classification::factory()->create(['city_id' => $city2->id, 'total' => 75]);

    $component = Livewire::test(MapsGoogle::class);
    $markers = $component->get('markers');

    expect($markers)->toHaveCount(2);
    expect($markers[0]['title'])->toBe('City One');
    expect($markers[1]['title'])->toBe('City Two');
});

it('eager loads city relationship', function () {
    $country = Country::factory()->create();
    $city = City::factory()->create([
        'country_id' => $country->id,
        'coordinates' => ['lat' => 40.7128, 'lng' => -74.0060],
    ]);
    Classification::factory()->create(['city_id' => $city->id]);

    Livewire::test(MapsGoogle::class)
        ->assertOk();
});

it('handles empty classifications', function () {
    $component = Livewire::test(MapsGoogle::class);
    $markers = $component->get('markers');

    expect($markers)->toBeArray();
    expect($markers)->toBeEmpty();
});

it('transforms coordinates correctly', function () {
    $country = Country::factory()->create();
    $city = City::factory()->create([
        'country_id' => $country->id,
        'coordinates' => ['lat' => 48.8566, 'lng' => 2.3522], // Paris coordinates
    ]);
    Classification::factory()->create(['city_id' => $city->id, 'total' => 88]);

    $component = Livewire::test(MapsGoogle::class);
    $markers = $component->get('markers');

    expect($markers[0]['lat'])->toBe(48.8566);
    expect($markers[0]['long'])->toBe(2.3522); // Note: 'lng' becomes 'long'
});

it('can be mounted without parameters', function () {
    Livewire::test(MapsGoogle::class)
        ->assertOk();
});

it('has all required properties for google maps', function () {
    $component = Livewire::test(MapsGoogle::class);

    expect($component->get('mapType'))->toBeString();
    expect($component->get('zoomLevel'))->toBeInt();
    expect($component->get('fitToBounds'))->toBeBool();
    expect($component->get('centerToBoundsCenter'))->toBeBool();
    expect($component->get('centerPoint'))->toBeArray();
    expect($component->get('markers'))->toBeArray();
});

it('center point has correct structure', function () {
    $component = Livewire::test(MapsGoogle::class);
    $centerPoint = $component->get('centerPoint');

    expect($centerPoint)->toHaveKey('lat');
    expect($centerPoint)->toHaveKey('long');
    expect($centerPoint['lat'])->toBeFloat();
    expect($centerPoint['long'])->toBeFloat();
});

it('markers have correct structure', function () {
    $country = Country::factory()->create();
    $city = City::factory()->create([
        'country_id' => $country->id,
        'display' => 'Test City',
        'coordinates' => ['lat' => 40.7128, 'lng' => -74.0060],
    ]);
    Classification::factory()->create([
        'city_id' => $city->id,
        'total' => 85,
    ]);

    $component = Livewire::test(MapsGoogle::class);
    $markers = $component->get('markers');

    foreach ($markers as $marker) {
        expect($marker)->toHaveKey('lat');
        expect($marker)->toHaveKey('long');
        expect($marker)->toHaveKey('title');
        expect($marker)->toHaveKey('info');
        expect($marker['lat'])->toBeFloat();
        expect($marker['long'])->toBeFloat();
        expect($marker['title'])->toBeString();
        expect($marker['info'])->toBeInt();
    }
});

it('excludes classifications with cities that have null coordinates', function () {
    $country = Country::factory()->create();
    $cityWithCoords = City::factory()->create([
        'country_id' => $country->id,
        'display' => 'Valid City',
        'coordinates' => ['lat' => 40.7128, 'lng' => -74.0060],
    ]);
    $cityWithNull = City::factory()->create([
        'country_id' => $country->id,
        'display' => 'Null Coords City',
        'coordinates' => null,
    ]);

    Classification::factory()->create(['city_id' => $cityWithCoords->id, 'total' => 90]);
    Classification::factory()->create(['city_id' => $cityWithNull->id, 'total' => 80]);

    $component = Livewire::test(MapsGoogle::class);
    $markers = $component->get('markers');

    expect($markers)->toHaveCount(1);
    expect($markers[0]['title'])->toBe('Valid City');
});

it('excludes classifications with cities that have empty coordinates', function () {
    $country = Country::factory()->create();
    $cityWithCoords = City::factory()->create([
        'country_id' => $country->id,
        'display' => 'Valid City',
        'coordinates' => ['lat' => 40.7128, 'lng' => -74.0060],
    ]);
    $cityWithEmpty = City::factory()->create([
        'country_id' => $country->id,
        'display' => 'Empty Coords City',
        'coordinates' => [],
    ]);

    Classification::factory()->create(['city_id' => $cityWithCoords->id, 'total' => 90]);
    Classification::factory()->create(['city_id' => $cityWithEmpty->id, 'total' => 80]);

    $component = Livewire::test(MapsGoogle::class);
    $markers = $component->get('markers');

    expect($markers)->toHaveCount(1);
    expect($markers[0]['title'])->toBe('Valid City');
});

it('excludes classifications with cities missing lat key', function () {
    $country = Country::factory()->create();
    $cityWithCoords = City::factory()->create([
        'country_id' => $country->id,
        'display' => 'Valid City',
        'coordinates' => ['lat' => 40.7128, 'lng' => -74.0060],
    ]);
    $cityMissingLat = City::factory()->create([
        'country_id' => $country->id,
        'display' => 'Missing Lat City',
        'coordinates' => ['lng' => -74.0060],
    ]);

    Classification::factory()->create(['city_id' => $cityWithCoords->id, 'total' => 90]);
    Classification::factory()->create(['city_id' => $cityMissingLat->id, 'total' => 80]);

    $component = Livewire::test(MapsGoogle::class);
    $markers = $component->get('markers');

    expect($markers)->toHaveCount(1);
    expect($markers[0]['title'])->toBe('Valid City');
});

it('excludes classifications with cities missing lng key', function () {
    $country = Country::factory()->create();
    $cityWithCoords = City::factory()->create([
        'country_id' => $country->id,
        'display' => 'Valid City',
        'coordinates' => ['lat' => 40.7128, 'lng' => -74.0060],
    ]);
    $cityMissingLng = City::factory()->create([
        'country_id' => $country->id,
        'display' => 'Missing Lng City',
        'coordinates' => ['lat' => 40.7128],
    ]);

    Classification::factory()->create(['city_id' => $cityWithCoords->id, 'total' => 90]);
    Classification::factory()->create(['city_id' => $cityMissingLng->id, 'total' => 80]);

    $component = Livewire::test(MapsGoogle::class);
    $markers = $component->get('markers');

    expect($markers)->toHaveCount(1);
    expect($markers[0]['title'])->toBe('Valid City');
});

it('excludes classifications with null city relationship', function () {
    $country = Country::factory()->create();
    $city = City::factory()->create([
        'country_id' => $country->id,
        'display' => 'Valid City',
        'coordinates' => ['lat' => 40.7128, 'lng' => -74.0060],
    ]);

    Classification::factory()->create(['city_id' => $city->id, 'total' => 90]);
    Classification::factory()->create(['city_id' => 99999, 'total' => 80]); // Non-existent city

    $component = Livewire::test(MapsGoogle::class);
    $markers = $component->get('markers');

    expect($markers)->toHaveCount(1);
    expect($markers[0]['title'])->toBe('Valid City');
});

it('handles mixed valid and invalid coordinates', function () {
    $country = Country::factory()->create();

    $validCity1 = City::factory()->create([
        'country_id' => $country->id,
        'display' => 'New York',
        'coordinates' => ['lat' => 40.7128, 'lng' => -74.0060],
    ]);
    $validCity2 = City::factory()->create([
        'country_id' => $country->id,
        'display' => 'London',
        'coordinates' => ['lat' => 51.5074, 'lng' => -0.1278],
    ]);
    $nullCoords = City::factory()->create([
        'country_id' => $country->id,
        'display' => 'Null City',
        'coordinates' => null,
    ]);
    $emptyCoords = City::factory()->create([
        'country_id' => $country->id,
        'display' => 'Empty City',
        'coordinates' => [],
    ]);
    $missingLat = City::factory()->create([
        'country_id' => $country->id,
        'display' => 'Missing Lat',
        'coordinates' => ['lng' => -74.0060],
    ]);

    Classification::factory()->create(['city_id' => $validCity1->id, 'total' => 95]);
    Classification::factory()->create(['city_id' => $validCity2->id, 'total' => 88]);
    Classification::factory()->create(['city_id' => $nullCoords->id, 'total' => 70]);
    Classification::factory()->create(['city_id' => $emptyCoords->id, 'total' => 65]);
    Classification::factory()->create(['city_id' => $missingLat->id, 'total' => 60]);

    $component = Livewire::test(MapsGoogle::class);
    $markers = $component->get('markers');

    expect($markers)->toHaveCount(2);

    $titles = array_column($markers, 'title');
    expect($titles)->toContain('New York');
    expect($titles)->toContain('London');
    expect($titles)->not->toContain('Null City');
    expect($titles)->not->toContain('Empty City');
    expect($titles)->not->toContain('Missing Lat');
});
