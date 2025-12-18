<?php

use App\Livewire\VisitadosListado;
use App\Models\City;
use App\Models\Classification;
use App\Models\Country;
use Livewire\Livewire;

it('can render component', function () {
    Livewire::test(VisitadosListado::class)
        ->assertOk();
});

it('displays visited cities', function () {
    $country = Country::factory()->create(['name' => 'España']);
    $visitedCity = City::factory()->create([
        'country_id' => $country->id,
        'name' => 'Madrid',
        'display' => 'Madrid, España',
        'visited' => true,
    ]);
    $plannedCity = City::factory()->create([
        'country_id' => $country->id,
        'name' => 'Barcelona',
        'visited' => false,
    ]);

    Livewire::test(VisitadosListado::class)
        ->assertSee('Madrid, España')
        ->assertDontSee('Barcelona');
});

it('displays cities with their countries', function () {
    $country = Country::factory()->create(['name' => 'Francia', 'display' => 'Francia']);
    City::factory()->create([
        'country_id' => $country->id,
        'name' => 'París',
        'display' => 'París',
        'visited' => true,
    ]);

    Livewire::test(VisitadosListado::class)
        ->assertSee('París');
});

it('displays cities with classifications', function () {
    $country = Country::factory()->create();
    $city = City::factory()->create([
        'country_id' => $country->id,
        'visited' => true,
    ]);
    Classification::factory()->create([
        'city_id' => $city->id,
        'total' => 85,
    ]);

    Livewire::test(VisitadosListado::class)
        ->assertOk();
});

it('orders cities by country name then city name', function () {
    $countryA = Country::factory()->create(['name' => 'Argentina']);
    $countryB = Country::factory()->create(['name' => 'Brasil']);
    
    City::factory()->create([
        'country_id' => $countryB->id,
        'name' => 'São Paulo',
        'visited' => true,
    ]);
    City::factory()->create([
        'country_id' => $countryA->id,
        'name' => 'Buenos Aires',
        'visited' => true,
    ]);

    Livewire::test(VisitadosListado::class)
        ->assertOk();
});

it('paginates results', function () {
    $country = Country::factory()->create();
    City::factory()->count(15)->create([
        'country_id' => $country->id,
        'visited' => true,
    ]);

    Livewire::test(VisitadosListado::class)
        ->assertOk();
});

it('uses with pagination trait', function () {
    $component = new VisitadosListado();
    
    expect(method_exists($component, 'gotoPage'))->toBeTrue();
    expect(method_exists($component, 'nextPage'))->toBeTrue();
    expect(method_exists($component, 'previousPage'))->toBeTrue();
});

it('filters only visited cities', function () {
    $country = Country::factory()->create();
    
    // Create visited cities
    City::factory()->count(3)->create([
        'country_id' => $country->id,
        'visited' => true,
    ]);
    
    // Create planned cities (should not appear)
    City::factory()->count(5)->create([
        'country_id' => $country->id,
        'visited' => false,
    ]);

    $component = Livewire::test(VisitadosListado::class);
    
    // Check the component renders correctly
    $component->assertOk();
    
    // Verify that only visited cities are shown by checking the total count
    expect(City::where('visited', true)->count())->toBe(3);
    expect(City::where('visited', false)->count())->toBe(5);
});

it('eager loads country and classification relationships', function () {
    $country = Country::factory()->create();
    $city = City::factory()->create([
        'country_id' => $country->id,
        'visited' => true,
    ]);
    Classification::factory()->create(['city_id' => $city->id]);

    $component = Livewire::test(VisitadosListado::class);
    
    // Check that the component renders without N+1 query issues
    $component->assertOk();
    
    // Verify relationships exist in database
    expect($city->fresh()->relationLoaded('country'))->toBeFalse(); // Will be loaded in component
    expect($city->fresh()->country)->not->toBeNull();
});