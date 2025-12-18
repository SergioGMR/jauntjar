<?php

use App\Models\User;

beforeEach(function () {
    // Browser tests will use the Laravel test server
});

describe('Home Page Browser Tests', function () {
    it('can visit home page', function () {
        $page = $this->visit('/');

        $page->assertSee(config('app.name'));
    });

    it('shows login link for guests', function () {
        $page = $this->visit('/');

        $page->assertSee('Iniciar Sesión');
    });

    it('has no javascript errors on home page', function () {
        $page = $this->visit('/');

        $page->assertNoJavaScriptErrors();
    });

    it('has no console errors on home page', function () {
        $page = $this->visit('/');

        $page->assertNoConsoleLogs();
    });
});

describe('Authentication Browser Tests', function () {
    it('can navigate to login page', function () {
        $page = $this->visit('/login');

        $page->assertPathIs('/login')
             ->assertSee('Bienvenido de nuevo');
    });

    it('login form shows email and password fields', function () {
        $page = $this->visit('/login');

        $page->assertPresent('input[name="email"]')
             ->assertPresent('input[name="password"]');
    });

    it('can fill and submit login form', function () {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $page = $this->visit('/login');

        $page->type('input[name="email"]', 'test@example.com')
             ->type('input[name="password"]', 'password123')
             ->submit()
             ->wait(2)
             ->assertPathIs('/dashboard');
    });
});

describe('Dashboard Browser Tests', function () {
    it('redirects guests to login', function () {
        $page = $this->visit('/dashboard');

        $page->assertPathIs('/login');
    });

    it('authenticated user can access dashboard after login', function () {
        $user = User::factory()->create([
            'email' => 'dashboard@example.com',
            'password' => bcrypt('password123'),
        ]);

        $page = $this->visit('/login');

        $page->type('input[name="email"]', 'dashboard@example.com')
             ->type('input[name="password"]', 'password123')
             ->submit()
             ->wait(2)
             ->assertPathIs('/dashboard');
    });
});
