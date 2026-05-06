<?php

use App\Http\Controllers\IncidentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ZoneController;

require __DIR__.'/auth.php';

// PUBLIC
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/infos', [PageController::class, 'infos'])->name('infos');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
// INCIDENTS (admin + incident)
Route::middleware(['auth', 'incidents.access'])->group(function () {
    Route::get('/dashboard', [IncidentController::class, 'index'])->name('dashboard');
    Route::resource('incidents', IncidentController::class);
});

// USERS (admin ONLY)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('users', UserController::class);
});

// Zones (resource partiel : index, store, destroy uniquement)
Route::get('/zones',                   [ZoneController::class, 'index'])->name('zones.index');
Route::post('/zones',                  [ZoneController::class, 'store'])->name('zones.store');
Route::delete('/zones/{zone}',         [ZoneController::class, 'destroy'])->name('zones.destroy');

// Incidents
Route::middleware(['auth', 'incidents.access'])->group(function () {

    Route::get('/poll-incidents', [IncidentController::class, 'poll'])
    ->name('incidents.poll');

    Route::get('/dashboard', [IncidentController::class, 'index'])->name('dashboard');

    Route::resource('incidents', IncidentController::class);
});