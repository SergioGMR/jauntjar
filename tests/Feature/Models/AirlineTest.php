<?php

use App\Models\Airline;
use App\Models\Budget;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('has many budgets', function () {
    $airline = Airline::factory()->create();
    Budget::factory()->count(2)->create(['airline_id' => $airline->id]);

    expect($airline->budgets)->toHaveCount(2);
    expect($airline->budgets->first())->toBeInstanceOf(Budget::class);
});

it('can check if is low cost', function () {
    $lowCost = Airline::factory()->create(['is_low_cost' => true]);
    $traditional = Airline::factory()->create(['is_low_cost' => false]);

    expect($lowCost->isLowCost())->toBeTrue();
    expect($traditional->isLowCost())->toBeFalse();
});

it('can get type display', function () {
    $lowCost = Airline::factory()->create(['is_low_cost' => true]);
    $traditional = Airline::factory()->create(['is_low_cost' => false]);

    expect($lowCost->getTypeDisplay())->toBe('Low Cost');
    expect($traditional->getTypeDisplay())->toBe('Tradicional');
});

it('casts is_low_cost to boolean', function () {
    $airline = Airline::factory()->create(['is_low_cost' => '1']);

    expect($airline->is_low_cost)->toBeBool();
});
