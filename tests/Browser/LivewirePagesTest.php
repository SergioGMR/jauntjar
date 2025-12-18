<?php

use App\Models\City;
use App\Models\Classification;
use App\Models\Country;
use App\Models\User;

beforeEach(function () {
    // Browser tests will use the Laravel test server
});

describe('Visitados Page Browser Tests', function () {
    it('requires authentication to access visitados', function () {
        $page = $this->visit('/visitados/listado');

        $page->assertPathIs('/login');
    });

    it('authenticated user can access visitados page', function () {
        $user = User::factory()->create([
            'email' => 'visitados@example.com',
            'password' => bcrypt('password123'),
        ]);

        $page = $this->visit('/login');

        $page->type('email', 'visitados@example.com')
             ->type('password', 'password123')
             ->click('Log in')
             ->assertPathIs('/dashboard');

        $page->navigate('/visitados/listado')
             ->assertPathIs('/visitados/listado');
    });
});

describe('Planeados Page Browser Tests', function () {
    it('requires authentication to access planeados', function () {
        $page = $this->visit('/planeados/listado');

        $page->assertPathIs('/login');
    });

    it('authenticated user can access planeados page', function () {
        $user = User::factory()->create([
            'email' => 'planeados@example.com',
            'password' => bcrypt('password123'),
        ]);

        $page = $this->visit('/login');

        $page->type('email', 'planeados@example.com')
             ->type('password', 'password123')
             ->click('Log in')
             ->assertPathIs('/dashboard');

        $page->navigate('/planeados/listado')
             ->assertPathIs('/planeados/listado');
    });
});

describe('Clasificados Page Browser Tests', function () {
    it('requires authentication to access clasificados', function () {
        $page = $this->visit('/clasificados/listado');

        $page->assertPathIs('/login');
    });

    it('authenticated user can access clasificados page', function () {
        $user = User::factory()->create([
            'email' => 'clasificados@example.com',
            'password' => bcrypt('password123'),
        ]);

        $page = $this->visit('/login');

        $page->type('email', 'clasificados@example.com')
             ->type('password', 'password123')
             ->click('Log in')
             ->assertPathIs('/dashboard');

        $page->navigate('/clasificados/listado')
             ->assertPathIs('/clasificados/listado');
    });
});
