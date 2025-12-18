<?php

use App\Models\City;
use App\Models\Classification;

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

it('has all required fillable fields', function () {
    $city = City::factory()->create();
    $classification = Classification::factory()->create([
        'uuid' => 'test-uuid-123',
        'city_id' => $city->id,
        'cost' => 80,
        'culture' => 90,
        'weather' => 70,
        'food' => 85,
        'total' => 81,
    ]);

    expect($classification->uuid)->toBe('test-uuid-123');
    expect($classification->city_id)->toBe($city->id);
    expect($classification->cost)->toBe(80);
    expect($classification->culture)->toBe(90);
    expect($classification->weather)->toBe(70);
    expect($classification->food)->toBe(85);
    expect($classification->total)->toBe(81);
});

it('uses soft deletes', function () {
    $classification = Classification::factory()->create();
    $classificationId = $classification->id;

    $classification->delete();

    expect(Classification::find($classificationId))->toBeNull();
    expect(Classification::withTrashed()->find($classificationId))->not->toBeNull();
});

it('has factory trait', function () {
    expect(Classification::factory())->toBeInstanceOf(\Illuminate\Database\Eloquent\Factories\Factory::class);
});

it('does not use timestamps', function () {
    $classification = new Classification();
    expect($classification->timestamps)->toBeFalse();
});

it('handles grade boundary values', function () {
    $grade90 = Classification::factory()->create(['total' => 90]);
    $grade80 = Classification::factory()->create(['total' => 80]);
    $grade70 = Classification::factory()->create(['total' => 70]);
    $grade60 = Classification::factory()->create(['total' => 60]);
    $grade59 = Classification::factory()->create(['total' => 59]);

    expect($grade90->getGrade())->toBe('Excelente');
    expect($grade80->getGrade())->toBe('Muy Bueno');
    expect($grade70->getGrade())->toBe('Bueno');
    expect($grade60->getGrade())->toBe('Regular');
    expect($grade59->getGrade())->toBe('Mejorable');
});

it('correctly identifies excellent scores', function () {
    $excellent = Classification::factory()->create(['total' => 90]);
    $notExcellent = Classification::factory()->create(['total' => 89]);

    expect($excellent->isExcellent())->toBeTrue();
    expect($notExcellent->isExcellent())->toBeFalse();
});

it('correctly identifies good scores', function () {
    $good = Classification::factory()->create(['total' => 70]);
    $notGood = Classification::factory()->create(['total' => 69]);

    expect($good->isGood())->toBeTrue();
    expect($notGood->isGood())->toBeFalse();
});
