<?php

use App\Models\City;
use App\Models\Classification;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('belongs to a city', function () {
    $city = City::factory()->create();
    $classification = Classification::factory()->create(['city_id' => $city->id]);

    expect($classification->city)->toBeInstanceOf(City::class);
    expect($classification->city->id)->toBe($city->id);
});

it('can get grade based on total score', function () {
    $excellent = Classification::factory()->create(['total' => 95]);
    $veryGood = Classification::factory()->create(['total' => 85]);
    $good = Classification::factory()->create(['total' => 75]);
    $regular = Classification::factory()->create(['total' => 65]);
    $poor = Classification::factory()->create(['total' => 45]);

    expect($excellent->getGrade())->toBe('Excelente');
    expect($veryGood->getGrade())->toBe('Muy Bueno');
    expect($good->getGrade())->toBe('Bueno');
    expect($regular->getGrade())->toBe('Regular');
    expect($poor->getGrade())->toBe('Mejorable');
});

it('can check if excellent', function () {
    $excellent = Classification::factory()->create(['total' => 95]);
    $notExcellent = Classification::factory()->create(['total' => 85]);

    expect($excellent->isExcellent())->toBeTrue();
    expect($notExcellent->isExcellent())->toBeFalse();
});

it('can check if good', function () {
    $good = Classification::factory()->create(['total' => 75]);
    $notGood = Classification::factory()->create(['total' => 65]);

    expect($good->isGood())->toBeTrue();
    expect($notGood->isGood())->toBeFalse();
});

it('can calculate total from individual scores', function () {
    $classification = Classification::factory()->create([
        'cost' => 80,
        'culture' => 90,
        'weather' => 70,
        'food' => 85,
    ]);

    $classification->calculateTotal();

    expect($classification->total)->toBe(81); // (80+90+70+85)/4 = 81.25, rounded = 81
});

it('casts scores to integers', function () {
    $classification = Classification::factory()->create([
        'cost' => '80',
        'culture' => '90',
        'weather' => '70',
        'food' => '85',
        'total' => '81',
    ]);

    expect($classification->cost)->toBeInt();
    expect($classification->culture)->toBeInt();
    expect($classification->weather)->toBeInt();
    expect($classification->food)->toBeInt();
    expect($classification->total)->toBeInt();
});
