<?php

use App\Models\City;
use App\Models\Destination;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('belongs to a city', function () {
    $city = City::factory()->create();
    $destination = Destination::factory()->create(['city_id' => $city->id]);

    expect($destination->city)->toBeInstanceOf(City::class);
    expect($destination->city->id)->toBe($city->id);
});

it('can calculate total cost', function () {
    $destination = Destination::factory()->create([
        'accommodation_price' => 200.50,
        'transport_price' => 75.25,
        'displacement_price' => 50.00,
    ]);

    expect($destination->getTotalCost())->toBe(325.75);
});

it('can format total cost', function () {
    $destination = Destination::factory()->create([
        'accommodation_price' => 200.50,
        'transport_price' => 75.25,
        'displacement_price' => 50.00,
    ]);

    expect($destination->getTotalCostFormatted())->toBe('€325.75');
});

it('can get accommodation stars display', function () {
    $destination = Destination::factory()->create(['accommodation_stars' => 5]);

    expect($destination->getAccommodationStarsDisplay())->toBe('⭐⭐⭐⭐⭐');
});

it('can calculate departure date', function () {
    $destination = Destination::factory()->create([
        'arrival_date' => Carbon::parse('2023-01-10'),
        'duration_days' => 7,
    ]);

    $departureDate = $destination->getDepartureDate();

    expect($departureDate->format('Y-m-d'))->toBe('2023-01-03');
});

it('returns null departure date when arrival date is missing', function () {
    $destination = Destination::factory()->create([
        'arrival_date' => null,
        'duration_days' => 7,
    ]);

    expect($destination->getDepartureDate())->toBeNull();
});

it('casts fields correctly', function () {
    $destination = Destination::factory()->create([
        'accommodation_price' => '200.50',
        'accommodation_stars' => '4',
        'duration_days' => '7',
        'arrival_date' => '2023-01-01 10:00:00',
    ]);

    expect($destination->accommodation_price)->toBeFloat();
    expect($destination->accommodation_stars)->toBeInt();
    expect($destination->duration_days)->toBeInt();
    expect($destination->arrival_date)->toBeInstanceOf(Carbon::class);
});
