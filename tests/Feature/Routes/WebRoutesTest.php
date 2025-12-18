<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Public Routes', function () {
    it('can access home page', function () {
        $this->get('/')
            ->assertOk()
            ->assertViewIs('welcome');
    });

    it('home page has expected content', function () {
        $this->get('/')
            ->assertOk()
            ->assertSee('Laravel');
    });
});

describe('Auth Protected Routes', function () {
    it('dashboard redirects guests to login', function () {
        $this->get('/dashboard')
            ->assertRedirect('/login');
    });

    it('authenticated users can access dashboard', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk()
            ->assertViewIs('dashboard');
    });

    it('visitados listado redirects guests to login', function () {
        $this->get('/visitados/listado')
            ->assertRedirect('/login');
    });

    it('authenticated users can access visitados listado', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/visitados/listado')
            ->assertOk();
    });

    it('planeados listado redirects guests to login', function () {
        $this->get('/planeados/listado')
            ->assertRedirect('/login');
    });

    it('authenticated users can access planeados listado', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/planeados/listado')
            ->assertOk();
    });

    it('clasificados listado redirects guests to login', function () {
        $this->get('/clasificados/listado')
            ->assertRedirect('/login');
    });

    it('authenticated users can access clasificados listado', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/clasificados/listado')
            ->assertOk();
    });
});

describe('Settings Routes', function () {
    it('settings redirects to settings/profile', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/settings')
            ->assertRedirect('settings/profile');
    });

    it('settings profile requires authentication', function () {
        $this->get('/settings/profile')
            ->assertRedirect('/login');
    });

    it('authenticated users can access settings profile', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/settings/profile')
            ->assertOk();
    });

    it('authenticated users can access settings password', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/settings/password')
            ->assertOk();
    });

    it('authenticated users can access settings appearance', function () {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/settings/appearance')
            ->assertOk();
    });
});
