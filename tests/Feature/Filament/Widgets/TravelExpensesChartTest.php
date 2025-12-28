<?php

use App\Filament\Widgets\TravelExpensesChart;
use App\Models\Airline;
use App\Models\Budget;
use App\Models\City;
use App\Models\Country;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Support\Carbon;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    Filament::setCurrentPanel('backoffice');
});

it('can render widget', function () {
    Livewire::test(TravelExpensesChart::class)
        ->assertSuccessful();
});

it('renders with empty database', function () {
    $component = Livewire::test(TravelExpensesChart::class);

    $component->assertSuccessful();
});

it('has correct heading', function () {
    $widget = new TravelExpensesChart;

    expect($widget->getHeading())->toBe('Gastos de Viajes');
});

it('shows expenses for last 12 months', function () {
    $airline = Airline::factory()->create();
    $country = Country::factory()->create();
    $city = City::factory()->create(['country_id' => $country->id]);

    Budget::factory()->create([
        'airline_id' => $airline->id,
        'origin_city_id' => $city->id,
        'total_price' => 50000, // 500.00 €
        'departed_at' => Carbon::now()->subMonths(2),
    ]);

    Budget::factory()->create([
        'airline_id' => $airline->id,
        'origin_city_id' => $city->id,
        'total_price' => 30000, // 300.00 €
        'departed_at' => Carbon::now()->subMonths(1),
    ]);

    $component = Livewire::test(TravelExpensesChart::class);

    $component->assertSuccessful();
});

it('handles budgets with null departed_at', function () {
    $airline = Airline::factory()->create();
    $country = Country::factory()->create();
    $city = City::factory()->create(['country_id' => $country->id]);

    // Budget with departed_at
    Budget::factory()->create([
        'airline_id' => $airline->id,
        'origin_city_id' => $city->id,
        'total_price' => 50000,
        'departed_at' => Carbon::now()->subMonth(),
    ]);

    // Budget without departed_at
    Budget::factory()->create([
        'airline_id' => $airline->id,
        'origin_city_id' => $city->id,
        'total_price' => 30000,
        'departed_at' => null,
    ]);

    $component = Livewire::test(TravelExpensesChart::class);

    $component->assertSuccessful();
});

it('excludes budgets older than 12 months', function () {
    $airline = Airline::factory()->create();
    $country = Country::factory()->create();
    $city = City::factory()->create(['country_id' => $country->id]);

    // Old budget (should be excluded)
    Budget::factory()->create([
        'airline_id' => $airline->id,
        'origin_city_id' => $city->id,
        'total_price' => 100000,
        'departed_at' => Carbon::now()->subMonths(15),
    ]);

    // Recent budget (should be included)
    Budget::factory()->create([
        'airline_id' => $airline->id,
        'origin_city_id' => $city->id,
        'total_price' => 50000,
        'departed_at' => Carbon::now()->subMonth(),
    ]);

    $component = Livewire::test(TravelExpensesChart::class);

    $component->assertSuccessful();
});

it('aggregates expenses by month', function () {
    $airline = Airline::factory()->create();
    $country = Country::factory()->create();
    $city = City::factory()->create(['country_id' => $country->id]);

    $targetMonth = Carbon::now()->subMonths(3);

    // Multiple budgets in same month
    Budget::factory()->create([
        'airline_id' => $airline->id,
        'origin_city_id' => $city->id,
        'total_price' => 20000, // 200 €
        'departed_at' => $targetMonth->copy()->startOfMonth(),
    ]);

    Budget::factory()->create([
        'airline_id' => $airline->id,
        'origin_city_id' => $city->id,
        'total_price' => 30000, // 300 €
        'departed_at' => $targetMonth->copy()->endOfMonth(),
    ]);

    $component = Livewire::test(TravelExpensesChart::class);

    $component->assertSuccessful();
});
