<?php

use App\Models\Budget;
use App\Models\Insurance;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('has many budgets', function () {
    $insurance = Insurance::factory()->create();
    Budget::factory()->count(2)->create(['insurance_id' => $insurance->id]);

    expect($insurance->budgets)->toHaveCount(2);
    expect($insurance->budgets->first())->toBeInstanceOf(Budget::class);
});

it('can check if has website', function () {
    $withWebsite = Insurance::factory()->create(['url' => 'https://example.com']);
    $withoutWebsite = Insurance::factory()->create(['url' => '']);

    expect($withWebsite->hasWebsite())->toBeTrue();
    expect($withoutWebsite->hasWebsite())->toBeFalse();
});

it('can get website host', function () {
    $insurance = Insurance::factory()->create(['url' => 'https://www.example.com/path']);

    expect($insurance->getWebsiteHost())->toBe('www.example.com');
});

it('returns null website host when no url', function () {
    $insurance = Insurance::factory()->create(['url' => '']);

    expect($insurance->getWebsiteHost())->toBeNull();
});
