<?php

use App\Models\Budget;
use App\Models\BudgetSegment;
use App\Models\City;

it('belongs to a budget', function () {
    $budget = Budget::factory()->create();
    $segment = BudgetSegment::factory()->create(['budget_id' => $budget->id]);

    expect($segment->budget)->toBeInstanceOf(Budget::class);
    expect($segment->budget->id)->toBe($budget->id);
});

it('belongs to origin city', function () {
    $city = City::factory()->create(['display' => 'Las Palmas']);
    $segment = BudgetSegment::factory()->create(['origin_city_id' => $city->id]);

    expect($segment->originCity)->toBeInstanceOf(City::class);
    expect($segment->originCity->display)->toBe('Las Palmas');
});

it('belongs to destination city', function () {
    $city = City::factory()->create(['display' => 'Londres']);
    $segment = BudgetSegment::factory()->create(['destination_city_id' => $city->id]);

    expect($segment->destinationCity)->toBeInstanceOf(City::class);
    expect($segment->destinationCity->display)->toBe('Londres');
});

it('has all required fillable fields', function () {
    $budget = Budget::factory()->create();
    $originCity = City::factory()->create();
    $destinationCity = City::factory()->create();

    $segment = BudgetSegment::factory()->create([
        'budget_id' => $budget->id,
        'origin_city_id' => $originCity->id,
        'destination_city_id' => $destinationCity->id,
        'stay_days' => 5,
        'order' => 1,
    ]);

    expect($segment->budget_id)->toBe($budget->id);
    expect($segment->origin_city_id)->toBe($originCity->id);
    expect($segment->destination_city_id)->toBe($destinationCity->id);
    expect($segment->stay_days)->toBe(5);
    expect($segment->order)->toBe(1);
});

it('casts stay_days and order to integer', function () {
    $segment = BudgetSegment::factory()->create([
        'stay_days' => '3',
        'order' => '2',
    ]);

    expect($segment->stay_days)->toBeInt();
    expect($segment->order)->toBeInt();
});

it('generates route description', function () {
    $originCity = City::factory()->create(['display' => 'Las Palmas']);
    $destinationCity = City::factory()->create(['display' => 'Londres']);

    $segment = BudgetSegment::factory()->create([
        'origin_city_id' => $originCity->id,
        'destination_city_id' => $destinationCity->id,
    ]);

    expect($segment->getRouteDescription())->toBe('Las Palmas → Londres');
});

it('has factory trait', function () {
    expect(BudgetSegment::factory())->toBeInstanceOf(\Illuminate\Database\Eloquent\Factories\Factory::class);
});

it('has transport_kind field', function () {
    $segment = BudgetSegment::factory()->create(['transport_kind' => 'plane']);

    expect($segment->transport_kind)->toBe('plane');
});

it('has transport_price field', function () {
    $segment = BudgetSegment::factory()->create(['transport_price' => 250]);

    expect($segment->transport_price)->toBe(250);
});

it('has stay_price field', function () {
    $segment = BudgetSegment::factory()->create(['stay_price' => 500]);

    expect($segment->stay_price)->toBe(500);
});

it('can be ordered by order field', function () {
    $budget = Budget::factory()->create(['is_open_jaw' => true]);
    
    $segment3 = BudgetSegment::factory()->create(['budget_id' => $budget->id, 'order' => 2]);
    $segment1 = BudgetSegment::factory()->create(['budget_id' => $budget->id, 'order' => 0]);
    $segment2 = BudgetSegment::factory()->create(['budget_id' => $budget->id, 'order' => 1]);

    $orderedSegments = $budget->segments()->orderBy('order')->get();

    expect($orderedSegments->first()->id)->toBe($segment1->id);
    expect($orderedSegments->last()->id)->toBe($segment3->id);
});
