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

it('has all required fillable fields', function () {
    $insurance = Insurance::factory()->create([
        'uuid' => 'test-uuid-123',
        'name' => 'Test Insurance',
        'display' => 'Test Insurance Display',
        'slug' => 'test-insurance',
        'url' => 'https://example.com/insurance',
    ]);

    expect($insurance->uuid)->toBe('test-uuid-123');
    expect($insurance->name)->toBe('Test Insurance');
    expect($insurance->display)->toBe('Test Insurance Display');
    expect($insurance->slug)->toBe('test-insurance');
    expect($insurance->url)->toBe('https://example.com/insurance');
});

it('uses soft deletes', function () {
    $insurance = Insurance::factory()->create();
    $insuranceId = $insurance->id;

    $insurance->delete();

    expect(Insurance::find($insuranceId))->toBeNull();
    expect(Insurance::withTrashed()->find($insuranceId))->not->toBeNull();
});

it('has factory trait', function () {
    expect(Insurance::factory())->toBeInstanceOf(\Illuminate\Database\Eloquent\Factories\Factory::class);
});

it('returns null for website without url', function () {
    $insurance = Insurance::factory()->create(['url' => '']);

    expect($insurance->hasWebsite())->toBeFalse();
    expect($insurance->getWebsiteHost())->toBeNull();
});
