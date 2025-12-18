<?php

use App\Models\User;

beforeEach(function () {
    // Browser tests will use the Laravel test server
});

describe('Home Page Browser Tests', function () {
    it('can visit home page', function () {
        $page = $this->visit('/');

        $page->assertSee('Laravel');
    });

    it('shows login and register links for guests', function () {
        $page = $this->visit('/');

        $page->assertSeeLink('Log in')
             ->assertSeeLink('Register');
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
        $page = $this->visit('/');

        $page->click('Log in')
             ->assertPathIs('/login')
             ->assertSee('Log in');
    });

    it('can navigate to register page', function () {
        $page = $this->visit('/');

        $page->click('Register')
             ->assertPathIs('/register');
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

        $page->type('email', 'test@example.com')
             ->type('password', 'password123')
             ->click('Log in')
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

        $page->type('email', 'dashboard@example.com')
             ->type('password', 'password123')
             ->click('Log in')
             ->assertPathIs('/dashboard')
             ->assertSee('Dashboard');
    });
});
