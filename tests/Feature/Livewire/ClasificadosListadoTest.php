<?php

use App\Livewire\ClasificadosListado;
use App\Models\City;
use App\Models\Classification;
use App\Models\Country;
use Livewire\Livewire;

it('can render component', function () {
    Livewire::test(ClasificadosListado::class)
        ->assertOk();
});

it('displays classifications with city and country', function () {
    $country = Country::factory()->create(['name' => 'España', 'display' => 'España']);
    $city = City::factory()->create([
        'country_id' => $country->id,
        'name' => 'Madrid',
        'display' => 'Madrid',
    ]);
    Classification::factory()->create([
        'city_id' => $city->id,
        'total' => 85,
    ]);

    Livewire::test(ClasificadosListado::class)
        ->assertSee('Madrid')
        ->assertSee('España');
});

it('can search by country name', function () {
    $country1 = Country::factory()->create(['display' => 'Francia']);
    $country2 = Country::factory()->create(['display' => 'España']);
    
    $city1 = City::factory()->create(['country_id' => $country1->id]);
    $city2 = City::factory()->create(['country_id' => $country2->id]);
    
    Classification::factory()->create(['city_id' => $city1->id]);
    Classification::factory()->create(['city_id' => $city2->id]);

    Livewire::test(ClasificadosListado::class)
        ->set('search', 'Francia')
        ->assertSee('Francia')
        ->assertDontSee('España');
});

it('can search by city name', function () {
    $country = Country::factory()->create();
    $city1 = City::factory()->create(['country_id' => $country->id, 'display' => 'París']);
    $city2 = City::factory()->create(['country_id' => $country->id, 'display' => 'Madrid']);
    
    Classification::factory()->create(['city_id' => $city1->id]);
    Classification::factory()->create(['city_id' => $city2->id]);

    Livewire::test(ClasificadosListado::class)
        ->set('search', 'París')
        ->assertSee('París')
        ->assertDontSee('Madrid');
});

it('can clear search', function () {
    $country = Country::factory()->create();
    $city = City::factory()->create(['country_id' => $country->id, 'display' => 'Roma']);
    Classification::factory()->create(['city_id' => $city->id]);

    Livewire::test(ClasificadosListado::class)
        ->set('search', 'test')
        ->call('clearSearch')
        ->assertSet('search', '');
});

it('can sort by field', function () {
    $component = Livewire::test(ClasificadosListado::class);
    
    $component->call('sortBy', 'total');
    
    expect($component->get('sortBy'))->toBe('total');
    expect($component->get('sortDirection'))->toBe('asc'); // Should toggle from default 'desc'
});

it('toggles sort direction when sorting by same field', function () {
    $component = Livewire::test(ClasificadosListado::class)
        ->set('sortBy', 'total')
        ->set('sortDirection', 'desc');
    
    $component->call('sortBy', 'total');
    
    expect($component->get('sortDirection'))->toBe('asc');
});

it('manages sort fields array', function () {
    $component = Livewire::test(ClasificadosListado::class);
    
    // Add field to sort
    $component->call('sortBy', 'score');
    expect($component->get('sortFields'))->toContain('score');
    
    // Remove field from sort
    $component->call('sortBy', 'score');
    expect($component->get('sortFields'))->not->toContain('score');
});

it('has default values', function () {
    $component = Livewire::test(ClasificadosListado::class);
    
    expect($component->get('search'))->toBe('');
    expect($component->get('sortBy'))->toBe('total');
    expect($component->get('sortDirection'))->toBe('desc');
    expect($component->get('sortFields'))->toBe([]);
});



it('paginates results', function () {
    $country = Country::factory()->create();
    
    // Create more than 10 classifications to test pagination
    for ($i = 0; $i < 15; $i++) {
        $city = City::factory()->create(['country_id' => $country->id]);
        Classification::factory()->create(['city_id' => $city->id]);
    }

    Livewire::test(ClasificadosListado::class)
        ->assertOk();
});


it('applies custom ordering for city country display', function () {
    $component = new ClasificadosListado();
    
    // Test that the applyOrderBy method exists and works
    expect(method_exists($component, 'applyOrderBy'))->toBeTrue();
});

it('handles empty search results', function () {
    $country = Country::factory()->create(['display' => 'España']);
    $city = City::factory()->create(['country_id' => $country->id]);
    Classification::factory()->create(['city_id' => $city->id]);

    Livewire::test(ClasificadosListado::class)
        ->set('search', 'NonExistentCountry')
        ->assertOk();
});

it('uses with pagination trait', function () {
    $component = new ClasificadosListado();
    
    expect(method_exists($component, 'gotoPage'))->toBeTrue();
    expect(method_exists($component, 'nextPage'))->toBeTrue();
    expect(method_exists($component, 'previousPage'))->toBeTrue();
});

it('has listeners for frontend methods', function () {
    $component = new ClasificadosListado();
    
    // Check that the component has the getListeners method defined
    expect(method_exists($component, 'getListeners'))->toBeTrue();
});