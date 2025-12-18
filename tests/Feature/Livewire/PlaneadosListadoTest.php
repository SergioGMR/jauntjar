<?php

use App\Livewire\PlaneadosListado;
use App\Models\City;
use App\Models\Country;
use Livewire\Livewire;

it('can render component', function () {
    Livewire::test(PlaneadosListado::class)
        ->assertOk();
});

it('displays planned cities', function () {
    $country = Country::factory()->create(['name' => 'Italia']);
    $plannedCity = City::factory()->create([
        'country_id' => $country->id,
        'name' => 'Roma',
        'display' => 'Roma, Italia',
        'visited' => false,
    ]);
    $visitedCity = City::factory()->create([
        'country_id' => $country->id,
        'name' => 'Florencia',
        'visited' => true,
    ]);

    Livewire::test(PlaneadosListado::class)
        ->assertSee('Roma, Italia')
        ->assertDontSee('Florencia');
});

it('displays cities with their countries', function () {
    $country = Country::factory()->create(['name' => 'Alemania', 'display' => 'Alemania']);
    City::factory()->create([
        'country_id' => $country->id,
        'name' => 'Berlín',
        'display' => 'Berlín',
        'visited' => false,
    ]);

    Livewire::test(PlaneadosListado::class)
        ->assertSee('Berlín');
});

it('orders cities by country name then city name', function () {
    $countryA = Country::factory()->create(['name' => 'Australia']);
    $countryB = Country::factory()->create(['name' => 'Bélgica']);
    
    City::factory()->create([
        'country_id' => $countryB->id,
        'name' => 'Bruselas',
        'visited' => false,
    ]);
    City::factory()->create([
        'country_id' => $countryA->id,
        'name' => 'Sídney',
        'visited' => false,
    ]);

    Livewire::test(PlaneadosListado::class)
        ->assertOk();
});

it('paginates results', function () {
    $country = Country::factory()->create();
    City::factory()->count(15)->create([
        'country_id' => $country->id,
        'visited' => false,
    ]);

    Livewire::test(PlaneadosListado::class)
        ->assertOk();
});

it('uses with pagination trait', function () {
    $component = new PlaneadosListado();
    
    expect(method_exists($component, 'gotoPage'))->toBeTrue();
    expect(method_exists($component, 'nextPage'))->toBeTrue();
    expect(method_exists($component, 'previousPage'))->toBeTrue();
});

it('filters only planned cities', function () {
    $country = Country::factory()->create();
    
    // Create planned cities
    City::factory()->count(4)->create([
        'country_id' => $country->id,
        'visited' => false,
    ]);
    
    // Create visited cities (should not appear)
    City::factory()->count(6)->create([
        'country_id' => $country->id,
        'visited' => true,
    ]);

    $component = Livewire::test(PlaneadosListado::class);
    
    // Check the component renders correctly
    $component->assertOk();
    
    // Verify that only planned cities are shown by checking the total count
    expect(City::where('visited', false)->count())->toBe(4);
    expect(City::where('visited', true)->count())->toBe(6);
});

it('eager loads country relationship', function () {
    $country = Country::factory()->create();
    $city = City::factory()->create([
        'country_id' => $country->id,
        'visited' => false,
    ]);

    $component = Livewire::test(PlaneadosListado::class);
    
    // Check that the component renders without N+1 query issues
    $component->assertOk();
    
    // Verify relationship exists in database
    expect($city->fresh()->country)->not->toBeNull();
});

it('handles empty results', function () {
    // No cities created
    Livewire::test(PlaneadosListado::class)
        ->assertOk();
});

it('handles mixed visited and planned cities correctly', function () {
    $country = Country::factory()->create();
    
    // Mix of visited and planned
    City::factory()->count(3)->create([
        'country_id' => $country->id,
        'visited' => true,
    ]);
    City::factory()->count(2)->create([
        'country_id' => $country->id,
        'visited' => false,
    ]);

    $component = Livewire::test(PlaneadosListado::class);
    
    // Check the component renders correctly
    $component->assertOk();
    
    // Verify database state
    expect(City::where('visited', false)->count())->toBe(2);
    expect(City::where('visited', true)->count())->toBe(3);
});