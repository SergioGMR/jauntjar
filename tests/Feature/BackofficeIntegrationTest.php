<?php

use App\Models\User;

it('planeados page contains link to backoffice cities', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('planeados.listado'));

    $response->assertStatus(200)
        ->assertSee('Gestionar Destinos')
        ->assertSee('Accede al panel de administración')
        ->assertSee(route('filament.backoffice.resources.cities.index'));
});

it('planeados page explains backoffice access correctly', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('planeados.listado'));

    $response->assertStatus(200)
        ->assertSee('¿Listo para planear tu próximo viaje?')
        ->assertSee('Accede al panel de administración para agregar nuevos destinos');
});
