<?php

use App\Livewire\VisitadosListado;
use App\Models\User;
use Livewire\Livewire;

it('can render visitados listado component', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(VisitadosListado::class)
        ->assertStatus(200);
});

it('displays correct column headers', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(VisitadosListado::class)
        ->assertSee('país')
        ->assertSee('ciudad')
        ->assertSee('idioma')
        ->assertSee('moneda')
        ->assertSee('puntuación')
        ->assertSee('paradas');
});
