<?php

use App\Models\Airline;
use App\Models\Budget;

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

it('has all required fillable fields', function () {
    $airline = Airline::factory()->create([
        'uuid' => 'test-uuid-123',
        'name' => 'Test Airline',
        'display' => 'Test Airline Display',
        'slug' => 'test-airline',
        'logo' => 'https://example.com/logo.png',
        'is_low_cost' => true,
    ]);

    expect($airline->uuid)->toBe('test-uuid-123');
    expect($airline->name)->toBe('Test Airline');
    expect($airline->display)->toBe('Test Airline Display');
    expect($airline->slug)->toBe('test-airline');
    expect($airline->logo)->toBe('https://example.com/logo.png');
    expect($airline->is_low_cost)->toBeTrue();
});

it('uses soft deletes', function () {
    $airline = Airline::factory()->create();
    $airlineId = $airline->id;

    $airline->delete();

    expect(Airline::find($airlineId))->toBeNull();
    expect(Airline::withTrashed()->find($airlineId))->not->toBeNull();
});

it('has factory trait', function () {
    expect(Airline::factory())->toBeInstanceOf(\Illuminate\Database\Eloquent\Factories\Factory::class);
});
