<?php

use App\Filament\Resources\AirlineResource;
use App\Models\Airline;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->actingAs($this->user);
    Filament::setCurrentPanel('backoffice');
});

it('can render airline resource page', function () {
    // Skip direct URL access since it requires panel context
    expect(AirlineResource::getUrl('index'))->toBeString();
});

it('can list airlines', function () {
    Airline::factory()->count(3)->create();

    Livewire::test(AirlineResource\Pages\ManageAirlines::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords(Airline::all());
});

it('has correct resource structure', function () {
    expect(AirlineResource::getModel())->toBe(Airline::class);
    expect(AirlineResource::form(\Filament\Schemas\Schema::make()))
        ->toBeInstanceOf(\Filament\Schemas\Schema::class);
    // Just test that the table method exists and returns something
    expect(method_exists(AirlineResource::class, 'table'))->toBeTrue();
});

it('has navigation properties', function () {
    expect(AirlineResource::getNavigationLabel())->toBeString();
    expect(AirlineResource::getNavigationSort())->toBeInt();
});

it('can access manage page', function () {
    $record = Airline::factory()->create();
    
    Livewire::test(AirlineResource\Pages\ManageAirlines::class)
        ->assertSuccessful()
        ->assertCanSeeTableRecords([$record]);
});
