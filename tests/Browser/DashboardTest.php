<?php

use App\Models\User;

describe('Dashboard Page Browser Tests', function () {
    it('requires authentication to access dashboard', function () {
        $page = $this->visit('/dashboard');

        $page->assertPathIs('/login');
    });

    it('authenticated user can access dashboard without errors', function () {
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

    it('dashboard loads map component without javascript errors', function () {
        $user = User::factory()->create([
            'email' => 'maptest@example.com',
            'password' => bcrypt('password123'),
        ]);

        $page = $this->visit('/login');

        $page->type('input[name="email"]', 'maptest@example.com')
            ->type('input[name="password"]', 'password123')
            ->submit()
            ->wait(2)
            ->assertPathIs('/dashboard')
            ->assertNoJavascriptErrors();
    });
});
