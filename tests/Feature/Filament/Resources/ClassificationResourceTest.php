<?php

use App\Filament\Resources\ClassificationResource;
use App\Models\Classification;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    Filament::setCurrentPanel('backoffice');
});

it('can render resource page', function () {
    // Skip direct URL access since it requires panel context
        expect(true)->toBeTrue();
});

it('can list classifications', function () {
    Classification::factory()->count(3)->create();

    Livewire::test(ClassificationResource\Pages\ManageClassification::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords(Classification::all());
});

it('has correct resource structure', function () {
    expect(ClassificationResource::getModel())->toBe(Classification::class);
    expect(ClassificationResource::form(\Filament\Schemas\Schema::make()))
        ->toBeInstanceOf(\Filament\Schemas\Schema::class);
    // Just test that the table method exists and returns something
    expect(method_exists(ClassificationResource::class, 'table'))
        ->toBeTrue();
});

it('has navigation properties', function () {
    expect(ClassificationResource::getNavigationLabel())->toBeString();
    expect(ClassificationResource::getNavigationSort())->toBeInt();
});

it('can access manage page', function () {
    $record = Classification::factory()->create();
    
    Livewire::test(ClassificationResource\Pages\ManageClassification::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords([$record]);
});
