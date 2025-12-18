<?php

use App\Models\City;
use App\Models\Destination;
use Carbon\Carbon;

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

it('has all required fillable fields', function () {
    $city = City::factory()->create();
    $destination = Destination::factory()->create([
        'city_id' => $city->id,
        'accommodation_stars' => 4,
        'accommodation_price' => 200.50,
        'transport_type' => 'taxi',
        'transport_price' => 75.00,
        'arrival_date' => '2023-01-10',
        'duration_days' => 7,
        'displacement' => 'flight',
        'displacement_price' => 150.00,
    ]);

    expect($destination->city_id)->toBe($city->id);
    expect($destination->accommodation_stars)->toBe(4);
    expect($destination->accommodation_price)->toBe(200.50);
    expect($destination->transport_type)->toBe('taxi');
    expect($destination->transport_price)->toBe(75.00);
    expect($destination->duration_days)->toBe(7);
    expect($destination->displacement)->toBe('flight');
    expect($destination->displacement_price)->toBe(150.00);
});

it('uses soft deletes', function () {
    $destination = Destination::factory()->create();
    $destinationId = $destination->id;

    $destination->delete();

    expect(Destination::find($destinationId))->toBeNull();
    expect(Destination::withTrashed()->find($destinationId))->not->toBeNull();
});

it('has factory trait', function () {
    expect(Destination::factory())->toBeInstanceOf(\Illuminate\Database\Eloquent\Factories\Factory::class);
});

it('handles different star ratings', function () {
    $oneStars = Destination::factory()->create(['accommodation_stars' => 1]);
    $threeStars = Destination::factory()->create(['accommodation_stars' => 3]);

    expect($oneStars->getAccommodationStarsDisplay())->toBe('⭐');
    expect($threeStars->getAccommodationStarsDisplay())->toBe('⭐⭐⭐');
});

it('handles zero cost correctly', function () {
    $destination = Destination::factory()->create([
        'accommodation_price' => 0,
        'transport_price' => 0,
        'displacement_price' => 0,
    ]);

    expect($destination->getTotalCost())->toBe(0.0);
    expect($destination->getTotalCostFormatted())->toBe('€0.00');
});
