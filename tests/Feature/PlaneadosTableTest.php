<?php

use App\Livewire\PlaneadosListado;
use App\Models\User;
use Livewire\Livewire;

it('can render planeados listado component', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(PlaneadosListado::class)
        ->assertStatus(200);
});

it('displays correct table headers', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(PlaneadosListado::class)
        ->assertSee('país')
        ->assertSee('ciudad')
        ->assertSee('idioma')
        ->assertSee('moneda')
        ->assertSee('PIBPC')
        ->assertSee('roaming')
        ->assertSee('visado')
        ->assertSee('escalas');
});
