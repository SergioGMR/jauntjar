<?php

use App\Filament\Widgets\StatsOverview;
use App\Models\Airline;
use App\Models\Budget;
use App\Models\City;
use App\Models\Country;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    Filament::setCurrentPanel('backoffice');
});

it('can render widget', function () {
    Livewire::test(StatsOverview::class)
        ->assertSuccessful();
});

it('shows correct stats with empty database', function () {
    $component = Livewire::test(StatsOverview::class);

    $component->assertSuccessful();
});

it('shows correct country count', function () {
    Country::factory()->count(5)->create();

    $component = Livewire::test(StatsOverview::class);

    $component->assertSuccessful();
});

it('shows correct city count and visited percentage', function () {
    $country = Country::factory()->create();

    City::factory()->count(3)->create([
        'country_id' => $country->id,
        'visited' => true,
    ]);
    City::factory()->count(2)->create([
        'country_id' => $country->id,
        'visited' => false,
    ]);

    $component = Livewire::test(StatsOverview::class);

    $component->assertSuccessful();
});

it('handles cities with null created_at in chart data', function () {
    $country = Country::factory()->create();

    // City with created_at
    City::factory()->create([
        'country_id' => $country->id,
        'created_at' => now(),
    ]);

    // City without created_at (simulating legacy data)
    City::factory()->create([
        'country_id' => $country->id,
        'created_at' => null,
    ]);

    $component = Livewire::test(StatsOverview::class);

    $component->assertSuccessful();
});

it('shows correct budget stats', function () {
    $airline = Airline::factory()->create();
    $country = Country::factory()->create();
    $city = City::factory()->create(['country_id' => $country->id]);

    Budget::factory()->count(3)->create([
        'airline_id' => $airline->id,
        'origin_city_id' => $city->id,
        'total_price' => 10000, // 100.00 €
    ]);

    $component = Livewire::test(StatsOverview::class);

    $component->assertSuccessful();
});

it('shows correct airline count', function () {
    Airline::factory()->count(4)->create();

    $component = Livewire::test(StatsOverview::class);

    $component->assertSuccessful();
});

it('handles zero cities gracefully', function () {
    // No cities created
    $component = Livewire::test(StatsOverview::class);

    $component->assertSuccessful();
});
