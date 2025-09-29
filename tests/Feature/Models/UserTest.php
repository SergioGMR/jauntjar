<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create initials from name', function () {
    $user = new User(['name' => 'John Doe']);

    expect($user->initials())->toBe('JD');
});

it('can create initials from single name', function () {
    $user = new User(['name' => 'Madonna']);

    expect($user->initials())->toBe('M');
});

it('can create initials from multiple names', function () {
    $user = new User(['name' => 'Jean Claude Van Damme']);

    expect($user->initials())->toBe('JCVD');
});

it('handles empty name gracefully', function () {
    $user = new User(['name' => '']);

    expect($user->initials())->toBe('');
});

it('has correct fillable attributes', function () {
    $user = new User;

    expect($user->getFillable())->toBe([
        'name',
        'email',
        'password',
    ]);
});

it('has correct hidden attributes', function () {
    $user = new User;

    expect($user->getHidden())->toBe([
        'password',
        'remember_token',
    ]);
});

it('casts email_verified_at to datetime', function () {
    $user = User::factory()->create(['email_verified_at' => '2023-01-01 00:00:00']);

    expect($user->email_verified_at)->toBeInstanceOf(\Illuminate\Support\Carbon::class);
});

it('hashes password automatically', function () {
    $user = User::factory()->create(['password' => 'plain-password']);

    expect($user->password)->not->toBe('plain-password');
    expect(strlen($user->password))->toBeGreaterThan(50); // Hashed passwords are long
});
