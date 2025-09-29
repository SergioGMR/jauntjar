<?php

use App\Models\City;
use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('has many cities', function () {
    $country = Country::factory()->create();
    City::factory()->count(3)->create(['country_id' => $country->id]);

    expect($country->cities)->toHaveCount(3);
    expect($country->cities->first())->toBeInstanceOf(City::class);
});

it('has many visited cities', function () {
    $country = Country::factory()->create();
    City::factory()->create(['country_id' => $country->id, 'visited' => true]);
    City::factory()->create(['country_id' => $country->id, 'visited' => false]);

    expect($country->visitedCities)->toHaveCount(1);
    expect($country->visitedCities->first()->visited)->toBeTrue();
});

it('has many planned cities', function () {
    $country = Country::factory()->create();
    City::factory()->create(['country_id' => $country->id, 'visited' => true]);
    City::factory()->create(['country_id' => $country->id, 'visited' => false]);

    expect($country->plannedCities)->toHaveCount(1);
    expect($country->plannedCities->first()->visited)->toBeFalse();
});

it('can check if requires visa', function () {
    $visaRequired = Country::factory()->create(['visa' => 'Sí']);
    $noVisa = Country::factory()->create(['visa' => 'No']);

    expect($visaRequired->requiresVisa())->toBeTrue();
    expect($noVisa->requiresVisa())->toBeFalse();
});

it('can check if has roaming included', function () {
    $roamingIncluded = Country::factory()->create(['roaming' => 'Incluido']);
    $noRoaming = Country::factory()->create(['roaming' => 'N/A']);

    expect($roamingIncluded->hasRoamingIncluded())->toBeTrue();
    expect($noRoaming->hasRoamingIncluded())->toBeFalse();
});

it('can format pibpc', function () {
    $country = Country::factory()->create(['pibpc' => 35000]);

    expect($country->getPibpcFormatted())->toBe('$35,000');
});

it('can calculate rights score', function () {
    $country = Country::factory()->create([
        'womens_rights' => 8,
        'lgtb_rights' => 6,
    ]);

    expect($country->getRightsScore())->toBe(7.0);
});

it('casts integer fields correctly', function () {
    $country = Country::factory()->create([
        'pibpc' => '50000',
        'womens_rights' => '9',
        'lgtb_rights' => '7',
    ]);

    expect($country->pibpc)->toBeInt();
    expect($country->womens_rights)->toBeInt();
    expect($country->lgtb_rights)->toBeInt();
});
