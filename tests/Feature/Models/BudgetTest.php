<?php

use App\Models\Airline;
use App\Models\Budget;
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