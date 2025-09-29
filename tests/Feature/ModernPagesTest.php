<?php

use App\Models\User;

it('can access modern visitados page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('visitados.listado'));

    $response->assertStatus(200)
        ->assertSee('Ciudades Visitadas')
        ->assertSee('Explora los lugares que ya has conocido')
        ->assertSee('Total Visitadas')
        ->assertSee('Países Visitados')
        ->assertSee('Puntuación Media')
        ->assertSee('Vuelos Directos')
        ->assertSeeLivewire('visitados-listado');
});

it('can access modern planeados page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('planeados.listado'));

    $response->assertStatus(200)
        ->assertSee('Ciudades Planeadas')
        ->assertSee('Descubre los destinos que tienes en mente')
        ->assertSee('Total Planeadas')
        ->assertSee('PIB Medio')
        ->assertSee('Requieren Visa')
        ->assertSee('Roaming Incluido')
        ->assertSee('¿Listo para planear tu próximo viaje?')
        ->assertSee('Gestionar Destinos')
        ->assertSeeLivewire('planeados-listado');
});
