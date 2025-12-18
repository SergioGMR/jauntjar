<?php

use App\Livewire\Actions\Logout;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

uses(RefreshDatabase::class);

it('can log out a user', function () {
    $user = User::factory()->create();
    
    // Log in the user
    Auth::login($user);
    expect(Auth::check())->toBeTrue();
    
    // Create logout action
    $logout = new Logout();
    
    // Execute logout
    $response = $logout();
    
    // Assert user is logged out
    expect(Auth::check())->toBeFalse();
    
    // Assert redirect to home
    expect($response->getTargetUrl())->toBe(url('/'));
});

it('invalidates session when logging out', function () {
    $user = User::factory()->create();
    
    // Start session and login
    Session::start();
    Auth::login($user);
    $originalSessionId = Session::getId();
    
    // Execute logout
    $logout = new Logout();
    $logout();
    
    // Session should be invalidated (different ID)
    expect(Session::getId())->not->toBe($originalSessionId);
});

it('regenerates csrf token on logout', function () {
    $user = User::factory()->create();
    
    // Start session and login
    Session::start();
    Auth::login($user);
    $originalToken = Session::token();
    
    // Execute logout
    $logout = new Logout();
    $logout();
    
    // Token should be regenerated
    expect(Session::token())->not->toBe($originalToken);
});

it('logs out using web guard', function () {
    $user = User::factory()->create();
    
    // Login with web guard
    Auth::guard('web')->login($user);
    expect(Auth::guard('web')->check())->toBeTrue();
    
    // Execute logout
    $logout = new Logout();
    $logout();
    
    // Assert logged out from web guard
    expect(Auth::guard('web')->check())->toBeFalse();
});

it('can be invoked as callable', function () {
    $user = User::factory()->create();
    Auth::login($user);
    
    $logout = new Logout();
    
    // Should be callable
    expect(is_callable($logout))->toBeTrue();
    
    // Can be invoked
    $response = $logout();
    
    expect($response)->toBeInstanceOf(\Illuminate\Http\RedirectResponse::class);
});

it('redirects to root path', function () {
    $user = User::factory()->create();
    Auth::login($user);
    
    $logout = new Logout();
    $response = $logout();
    
    expect($response->getTargetUrl())->toBe(url('/'));
});

it('works when no user is logged in', function () {
    // Ensure no user is logged in
    Auth::logout();
    expect(Auth::check())->toBeFalse();
    
    // Should not throw error
    $logout = new Logout();
    $response = $logout();
    
    expect($response->getTargetUrl())->toBe(url('/'));
});

it('clears all session data', function () {
    $user = User::factory()->create();
    
    // Start session, add data, and login
    Session::start();
    Session::put('test_key', 'test_value');
    Auth::login($user);
    
    expect(Session::has('test_key'))->toBeTrue();
    
    // Execute logout
    $logout = new Logout();
    $logout();
    
    // Session data should be cleared
    expect(Session::has('test_key'))->toBeFalse();
});

it('uses correct namespace', function () {
    $logout = new Logout();
    
    expect(get_class($logout))->toBe('App\Livewire\Actions\Logout');
});

it('has invoke method', function () {
    expect(method_exists(Logout::class, '__invoke'))->toBeTrue();
});