<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

Route::middleware(['auth'])->name('visitados.')->prefix('visitados')->group(function () {
    Route::view('/listado', 'visitados.listado')->name('listado');
});

Route::middleware(['auth'])->name('planeados.')->prefix('planeados')->group(function () {
    Route::view('/listado', 'planeados.listado')->name('listado');
});

Route::middleware(['auth'])->name('clasificados.')->prefix('clasificados')->group(function () {
    Route::view('/listado', 'clasificados.listado')->name('listado');
});

require __DIR__ . '/auth.php';
