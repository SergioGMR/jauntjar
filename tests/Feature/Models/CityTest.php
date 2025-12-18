<?php

use App\Models\Budget;
use App\Models\City;
use App\Models\Classification;
use App\Models\Country;
use App\Models\Destination;

it('belongs to a country', function () {
    $country = Country::factory()->create();
    $city = City::factory()->create(['country_id' => $country->id]);

    expect($city->country)->toBeInstanceOf(Country::class);
    expect($city->country->id)->toBe($country->id);
});

it('has one classification', function () {
    $city = City::factory()->create();
    $classification = Classification::factory()->create(['city_id' => $city->id]);

    expect($city->classification)->toBeInstanceOf(Classification::class);
    expect($city->classification->id)->toBe($classification->id);
});

it('has many destinations', function () {
    $city = City::factory()->create();
    Destination::factory()->count(2)->create(['city_id' => $city->id]);

    expect($city->destinations)->toHaveCount(2);
    expect($city->destinations->first())->toBeInstanceOf(Destination::class);
});

it('can check if visited', function () {
    $visitedCity = City::factory()->create(['visited' => true]);
    $plannedCity = City::factory()->create(['visited' => false]);

    expect($visitedCity->isVisited())->toBeTrue();
    expect($plannedCity->isVisited())->toBeFalse();
});

it('can check if planned', function () {
    $visitedCity = City::factory()->create(['visited' => true]);
    $plannedCity = City::factory()->create(['visited' => false]);

    expect($visitedCity->isPlanned())->toBeFalse();
    expect($plannedCity->isPlanned())->toBeTrue();
});

it('can check if has stops', function () {
    $directCity = City::factory()->create(['stops' => 0]);
    $cityWithStops = City::factory()->create(['stops' => 2]);

    expect($directCity->hasStops())->toBeFalse();
    expect($cityWithStops->hasStops())->toBeTrue();
});

it('can get stops description', function () {
    $directCity = City::factory()->create(['stops' => 0]);
    $cityWithStops = City::factory()->create(['stops' => 2]);
    $cityWithOneStop = City::factory()->create(['stops' => 1]);

    expect($directCity->getStopsDescription())->toBe('Directo');
    expect($cityWithStops->getStopsDescription())->toBe('2 paradas');
    expect($cityWithOneStop->getStopsDescription())->toBe('1 paradas');
});

it('can get classification score', function () {
    $city = City::factory()->create();
    $classification = Classification::factory()->create([
        'city_id' => $city->id,
        'total' => 85,
    ]);

    expect($city->fresh()->getClassificationScore())->toBe(85);
});

it('returns null for classification score when no classification', function () {
    $city = City::factory()->create();

    expect($city->getClassificationScore())->toBeNull();
});

it('can get classification display', function () {
    $city = City::factory()->create();
    $classification = Classification::factory()->create([
        'city_id' => $city->id,
        'total' => 85,
    ]);

    expect($city->fresh()->getClassificationDisplay())->toBe('85/100');
});

it('returns default classification display when no classification', function () {
    $city = City::factory()->create();

    expect($city->getClassificationDisplay())->toBe('Sin clasificar');
});

it('can scope visited cities', function () {
    City::factory()->create(['visited' => true]);
    City::factory()->create(['visited' => false]);

    $visitedCities = City::visited()->get();

    expect($visitedCities)->toHaveCount(1);
    expect($visitedCities->first()->visited)->toBeTrue();
});

it('can scope planned cities', function () {
    City::factory()->create(['visited' => true]);
    City::factory()->create(['visited' => false]);

    $plannedCities = City::planned()->get();

    expect($plannedCities)->toHaveCount(1);
    expect($plannedCities->first()->visited)->toBeFalse();
});

it('can scope cities without stops', function () {
    City::factory()->create(['stops' => 0]);
    City::factory()->create(['stops' => 2]);

    $directCities = City::withoutStops()->get();

    expect($directCities)->toHaveCount(1);
    expect($directCities->first()->stops)->toBe(0);
});

it('casts visited to boolean', function () {
    $city = City::factory()->create(['visited' => '1']);
    
    expect($city->visited)->toBeBool();
    expect($city->visited)->toBeTrue();
});

it('casts coordinates to array', function () {
    $coordinates = ['lat' => 40.7128, 'lng' => -74.0060];
    $city = City::factory()->create(['coordinates' => $coordinates]);
    
    expect($city->coordinates)->toBeArray();
    expect($city->coordinates['lat'])->toBe(40.7128);
    expect($city->coordinates['lng'])->toBe(-74.0060);
});

it('has all required fillable fields', function () {
    $city = City::factory()->create([
        'uuid' => 'test-uuid-123',
        'name' => 'Test City',
        'display' => 'Test City Display',
        'slug' => 'test-city',
        'stops' => 1,
        'visited' => true,
        'coordinates' => ['lat' => 40.7128, 'lng' => -74.0060],
    ]);
    
    expect($city->uuid)->toBe('test-uuid-123');
    expect($city->name)->toBe('Test City');
    expect($city->display)->toBe('Test City Display');
    expect($city->slug)->toBe('test-city');
    expect($city->stops)->toBe(1);
    expect($city->visited)->toBeTrue();
    expect($city->coordinates)->toBeArray();
});

it('uses soft deletes', function () {
    $city = City::factory()->create();
    $cityId = $city->id;
    
    $city->delete();
    
    expect(City::find($cityId))->toBeNull();
    expect(City::withTrashed()->find($cityId))->not->toBeNull();
});

it('does not use timestamps', function () {
    $city = new City();
    expect($city->timestamps)->toBeFalse();
});

it('has factory trait', function () {
    expect(City::factory())->toBeInstanceOf(\Illuminate\Database\Eloquent\Factories\Factory::class);
});

it('can handle multiple scopes chained', function () {
    City::factory()->create(['visited' => false, 'stops' => 0]);
    City::factory()->create(['visited' => false, 'stops' => 2]);
    City::factory()->create(['visited' => true, 'stops' => 0]);
    
    $plannedDirectCities = City::planned()->withoutStops()->get();
    
    expect($plannedDirectCities)->toHaveCount(1);
    expect($plannedDirectCities->first()->visited)->toBeFalse();
    expect($plannedDirectCities->first()->stops)->toBe(0);
});

it('can work with different stops values', function () {
    $cityWithManyStops = City::factory()->create(['stops' => 5]);
    
    expect($cityWithManyStops->hasStops())->toBeTrue();
    expect($cityWithManyStops->getStopsDescription())->toBe('5 paradas');
});

it('handles classification relationship properly', function () {
    $city = City::factory()->create();
    
    // Initially no classification
    expect($city->classification)->toBeNull();
    expect($city->getClassificationScore())->toBeNull();
    expect($city->getClassificationDisplay())->toBe('Sin clasificar');
    
    // After adding classification
    $classification = Classification::factory()->create([
        'city_id' => $city->id,
        'total' => 92,
    ]);
    
    $city = $city->fresh();
    expect($city->classification)->not->toBeNull();
    expect($city->getClassificationScore())->toBe(92);
    expect($city->getClassificationDisplay())->toBe('92/100');
});

it('handles coordinates edge cases', function () {
    $cityWithNullCoordinates = City::factory()->create(['coordinates' => null]);
    $cityWithEmptyCoordinates = City::factory()->create(['coordinates' => []]);
    
    expect($cityWithNullCoordinates->coordinates)->toBeNull();
    expect($cityWithEmptyCoordinates->coordinates)->toBeArray();
    expect($cityWithEmptyCoordinates->coordinates)->toBeEmpty();
});

it('can get uuid', function () {
    $city = City::factory()->create(['uuid' => 'test-uuid-123']);
    
    expect($city->getUuid())->toBe('test-uuid-123');
});

it('can get slug', function () {
    $city = City::factory()->create(['slug' => 'test-city-slug']);
    
    expect($city->getSlug())->toBe('test-city-slug');
});

it('can get coordinates', function () {
    $coordinates = ['lat' => 40.7128, 'lng' => -74.0060];
    $city = City::factory()->create(['coordinates' => $coordinates]);
    
    expect($city->getCoordinates())->toBe($coordinates);
});

it('can check if has coordinates', function () {
    $cityWithCoordinates = City::factory()->create([
        'coordinates' => ['lat' => 40.7128, 'lng' => -74.0060]
    ]);
    $cityWithoutCoordinates = City::factory()->create(['coordinates' => null]);
    $cityWithIncompleteCoordinates = City::factory()->create([
        'coordinates' => ['lat' => 40.7128]
    ]);
    
    expect($cityWithCoordinates->hasCoordinates())->toBeTrue();
    expect($cityWithoutCoordinates->hasCoordinates())->toBeFalse();
    expect($cityWithIncompleteCoordinates->hasCoordinates())->toBeFalse();
});

it('can get latitude and longitude', function () {
    $city = City::factory()->create([
        'coordinates' => ['lat' => 40.7128, 'lng' => -74.0060]
    ]);
    $cityWithoutCoordinates = City::factory()->create(['coordinates' => null]);
    
    expect($city->getLatitude())->toBe(40.7128);
    expect($city->getLongitude())->toBe(-74.0060);
    expect($cityWithoutCoordinates->getLatitude())->toBeNull();
    expect($cityWithoutCoordinates->getLongitude())->toBeNull();
});

it('can check if has classification', function () {
    $cityWithClassification = City::factory()->create();
    Classification::factory()->create(['city_id' => $cityWithClassification->id]);
    
    $cityWithoutClassification = City::factory()->create();
    
    expect($cityWithClassification->fresh()->hasClassification())->toBeTrue();
    expect($cityWithoutClassification->hasClassification())->toBeFalse();
});

it('can access all getter methods', function () {
    $coordinates = ['lat' => 40.7128, 'lng' => -74.0060];
    $city = City::factory()->create([
        'uuid' => 'test-uuid',
        'slug' => 'test-slug',
        'coordinates' => $coordinates,
    ]);
    
    Classification::factory()->create([
        'city_id' => $city->id,
        'total' => 88,
    ]);
    
    $city = $city->fresh();
    
    expect($city->getUuid())->toBe('test-uuid');
    expect($city->getSlug())->toBe('test-slug');
    expect($city->getCoordinates())->toBe($coordinates);
    expect($city->hasCoordinates())->toBeTrue();
    expect($city->getLatitude())->toBe(40.7128);
    expect($city->getLongitude())->toBe(-74.0060);
    expect($city->hasClassification())->toBeTrue();
    expect($city->getClassificationScore())->toBe(88);
    expect($city->getClassificationDisplay())->toBe('88/100');
});
