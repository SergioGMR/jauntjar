<?php

use App\Models\Airline;
use App\Models\Budget;
use App\Models\BudgetSegment;
use App\Models\City;
use App\Models\Insurance;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('belongs to airline and insurance', function () {
    $airline = Airline::factory()->create();
    $insurance = Insurance::factory()->create();

    $budget = Budget::factory()->create([
        'airline_id' => $airline->id,
        'insurance_id' => $insurance->id,
    ]);

    expect($budget->airline)->toBeInstanceOf(Airline::class);
    expect($budget->airline->id)->toBe($airline->id);
    expect($budget->insurance)->toBeInstanceOf(Insurance::class);
    expect($budget->insurance->id)->toBe($insurance->id);
});

it('can get name and display', function () {
    $budget = Budget::factory()->create([
        'name' => 'Test Budget',
        'display' => 'Test Budget Display',
    ]);

    expect($budget->name)->toBe('Test Budget');
    expect($budget->display)->toBe('Test Budget Display');
    expect($budget->getName())->toBe('Test Budget');
    expect($budget->getDisplay())->toBe('Test Budget Display');
});

it('has slug field', function () {
    $budget = Budget::factory()->create([
        'slug' => 'test-budget-slug',
    ]);

    expect($budget->slug)->toBe('test-budget-slug');
});

it('has all required fillable fields', function () {
    $budget = Budget::factory()->create([
        'name' => 'Budget Name',
        'display' => 'Budget Display',
        'slug' => 'budget-slug',
        'flight_ticket_price' => 500,
        'insurance_price' => 100,
        'total_price' => 600,
    ]);

    expect($budget->name)->toBe('Budget Name');
    expect($budget->display)->toBe('Budget Display');
    expect($budget->slug)->toBe('budget-slug');
    expect($budget->flight_ticket_price)->toBe(500);
    expect($budget->insurance_price)->toBe(100);
    expect($budget->total_price)->toBe(600);
});

it('uses soft deletes', function () {
    $budget = Budget::factory()->create();
    $budgetId = $budget->id;

    $budget->delete();

    expect(Budget::find($budgetId))->toBeNull();
    expect(Budget::withTrashed()->find($budgetId))->not->toBeNull();
});

it('has factory trait', function () {
    expect(Budget::factory())->toBeInstanceOf(\Illuminate\Database\Eloquent\Factories\Factory::class);
});

it('casts departed_at and arrived_at to datetime', function () {
    $budget = Budget::factory()->create([
        'departed_at' => '2023-01-01 10:00:00',
        'arrived_at' => '2023-01-08 15:00:00',
    ]);

    expect($budget->departed_at)->toBeInstanceOf(\Carbon\Carbon::class);
    expect($budget->arrived_at)->toBeInstanceOf(\Carbon\Carbon::class);
});

it('handles null datetime fields', function () {
    $budget = Budget::factory()->create([
        'departed_at' => null,
        'arrived_at' => null,
    ]);

    expect($budget->departed_at)->toBeNull();
    expect($budget->arrived_at)->toBeNull();
});

it('can be created with airline and insurance relationships', function () {
    $airline = Airline::factory()->create(['name' => 'Test Airline']);
    $insurance = Insurance::factory()->create(['name' => 'Test Insurance']);

    $budget = Budget::factory()->create([
        'airline_id' => $airline->id,
        'insurance_id' => $insurance->id,
    ]);

    expect($budget->airline->name)->toBe('Test Airline');
    expect($budget->insurance->name)->toBe('Test Insurance');
});

it('validates relationship integrity', function () {
    $budget = Budget::factory()->create();

    expect($budget->airline_id)->not->toBeNull();
    expect($budget->insurance_id)->not->toBeNull();
    expect($budget->airline)->toBeInstanceOf(Airline::class);
    expect($budget->insurance)->toBeInstanceOf(Insurance::class);
});

it('can get slug', function () {
    $budget = Budget::factory()->create(['slug' => 'my-budget-slug']);

    expect($budget->getSlug())->toBe('my-budget-slug');
});

it('can get total price', function () {
    $budget = Budget::factory()->create(['total_price' => 1500]);

    expect($budget->getTotalPrice())->toBe(1500);
});

it('can get flight ticket price', function () {
    $budget = Budget::factory()->create(['flight_ticket_price' => 800]);

    expect($budget->getFlightTicketPrice())->toBe(800);
});

it('can get insurance price', function () {
    $budget = Budget::factory()->create(['insurance_price' => 150]);

    expect($budget->getInsurancePrice())->toBe(150);
});

it('can check if has insurance', function () {
    $budgetWithInsurance = Budget::factory()->create();

    expect($budgetWithInsurance->hasInsurance())->toBeTrue();
});

it('can check if has airline', function () {
    $budgetWithAirline = Budget::factory()->create();

    expect($budgetWithAirline->hasAirline())->toBeTrue();
});

it('can access all getter methods', function () {
    $budget = Budget::factory()->create([
        'name' => 'Test Budget',
        'display' => 'Test Display',
        'slug' => 'test-slug',
        'total_price' => 1000,
        'flight_ticket_price' => 700,
        'insurance_price' => 100,
    ]);

    expect($budget->getName())->toBe('Test Budget');
    expect($budget->getDisplay())->toBe('Test Display');
    expect($budget->getSlug())->toBe('test-slug');
    expect($budget->getTotalPrice())->toBe(1000);
    expect($budget->getFlightTicketPrice())->toBe(700);
    expect($budget->getInsurancePrice())->toBe(100);
    expect($budget->hasInsurance())->toBeTrue();
    expect($budget->hasAirline())->toBeTrue();
});

it('can be marked as open jaw', function () {
    $budget = Budget::factory()->create([
        'is_open_jaw' => true,
    ]);

    expect($budget->is_open_jaw)->toBeTrue();
    expect($budget->isOpenJaw())->toBeTrue();
});

it('has origin city relationship for open jaw', function () {
    $city = City::factory()->create(['display' => 'Las Palmas']);
    $budget = Budget::factory()->create([
        'is_open_jaw' => true,
        'origin_city_id' => $city->id,
    ]);

    expect($budget->originCity)->toBeInstanceOf(City::class);
    expect($budget->originCity->display)->toBe('Las Palmas');
});

it('has segments relationship', function () {
    $budget = Budget::factory()->create(['is_open_jaw' => true]);

    expect($budget->segments)->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
});

it('can add segments to open jaw budget', function () {
    $lpa = City::factory()->create(['display' => 'Las Palmas']);
    $lon = City::factory()->create(['display' => 'Londres']);
    $cdg = City::factory()->create(['display' => 'París']);

    $budget = Budget::factory()->create([
        'is_open_jaw' => true,
        'origin_city_id' => $lpa->id,
    ]);

    BudgetSegment::factory()->create([
        'budget_id' => $budget->id,
        'origin_city_id' => $lpa->id,
        'destination_city_id' => $lon->id,
        'stay_days' => 4,
        'order' => 0,
    ]);

    BudgetSegment::factory()->create([
        'budget_id' => $budget->id,
        'origin_city_id' => $lon->id,
        'destination_city_id' => $cdg->id,
        'stay_days' => 3,
        'order' => 1,
    ]);

    BudgetSegment::factory()->create([
        'budget_id' => $budget->id,
        'origin_city_id' => $cdg->id,
        'destination_city_id' => $lpa->id,
        'stay_days' => 0,
        'order' => 2,
    ]);

    $budget->refresh();

    expect($budget->segments)->toHaveCount(3);
    expect($budget->segments->first()->originCity->display)->toBe('Las Palmas');
    expect($budget->segments->first()->destinationCity->display)->toBe('Londres');
});

it('calculates total stay days', function () {
    $lpa = City::factory()->create();
    $lon = City::factory()->create();
    $cdg = City::factory()->create();

    $budget = Budget::factory()->create([
        'is_open_jaw' => true,
        'origin_city_id' => $lpa->id,
    ]);

    BudgetSegment::factory()->create([
        'budget_id' => $budget->id,
        'origin_city_id' => $lpa->id,
        'destination_city_id' => $lon->id,
        'stay_days' => 4,
        'order' => 0,
    ]);

    BudgetSegment::factory()->create([
        'budget_id' => $budget->id,
        'origin_city_id' => $lon->id,
        'destination_city_id' => $cdg->id,
        'stay_days' => 3,
        'order' => 1,
    ]);

    BudgetSegment::factory()->create([
        'budget_id' => $budget->id,
        'origin_city_id' => $cdg->id,
        'destination_city_id' => $lpa->id,
        'stay_days' => 0,
        'order' => 2,
    ]);

    $budget->refresh();

    expect($budget->getTotalStayDays())->toBe(7);
});

it('calculates return date from departure date and stay days', function () {
    $lpa = City::factory()->create();
    $lon = City::factory()->create();
    $cdg = City::factory()->create();

    $budget = Budget::factory()->create([
        'is_open_jaw' => true,
        'origin_city_id' => $lpa->id,
        'departed_at' => '2024-06-01',
    ]);

    BudgetSegment::factory()->create([
        'budget_id' => $budget->id,
        'origin_city_id' => $lpa->id,
        'destination_city_id' => $lon->id,
        'stay_days' => 4,
        'order' => 0,
    ]);

    BudgetSegment::factory()->create([
        'budget_id' => $budget->id,
        'origin_city_id' => $lon->id,
        'destination_city_id' => $cdg->id,
        'stay_days' => 3,
        'order' => 1,
    ]);

    BudgetSegment::factory()->create([
        'budget_id' => $budget->id,
        'origin_city_id' => $cdg->id,
        'destination_city_id' => $lpa->id,
        'stay_days' => 0,
        'order' => 2,
    ]);

    $budget->refresh();

    $returnDate = $budget->getCalculatedReturnDate();

    expect($returnDate)->not->toBeNull();
    expect($returnDate->format('Y-m-d'))->toBe('2024-06-08');
});

it('returns null for calculated return date when no departure date', function () {
    $budget = Budget::factory()->create([
        'is_open_jaw' => true,
        'departed_at' => null,
    ]);

    expect($budget->getCalculatedReturnDate())->toBeNull();
});

it('generates itinerary description for open jaw', function () {
    $lpa = City::factory()->create(['display' => 'Las Palmas']);
    $lon = City::factory()->create(['display' => 'Londres']);
    $cdg = City::factory()->create(['display' => 'París']);

    $budget = Budget::factory()->create([
        'is_open_jaw' => true,
        'origin_city_id' => $lpa->id,
    ]);

    BudgetSegment::factory()->create([
        'budget_id' => $budget->id,
        'origin_city_id' => $lpa->id,
        'destination_city_id' => $lon->id,
        'stay_days' => 4,
        'order' => 0,
    ]);

    BudgetSegment::factory()->create([
        'budget_id' => $budget->id,
        'origin_city_id' => $lon->id,
        'destination_city_id' => $cdg->id,
        'stay_days' => 3,
        'order' => 1,
    ]);

    BudgetSegment::factory()->create([
        'budget_id' => $budget->id,
        'origin_city_id' => $cdg->id,
        'destination_city_id' => $lpa->id,
        'stay_days' => 0,
        'order' => 2,
    ]);

    $budget->refresh();

    $description = $budget->getItineraryDescription();

    expect($description)->toBe('Las Palmas → Londres (4 días) → París (3 días) → Las Palmas');
});

it('returns empty itinerary description for non-open jaw', function () {
    $budget = Budget::factory()->create(['is_open_jaw' => false]);

    expect($budget->getItineraryDescription())->toBe('');
});
