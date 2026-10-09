<?php

use Illuminate\Support\Facades\Route;

// Phase 1 "Sockets": Foundational Livewire Component Routes
// These currently use the stubbed components in App\Livewire

Route::get('/', \App\Livewire\Pages\Home::class)->name('home');
Route::get('/how-it-works', \App\Livewire\Pages\HowItWorks::class)->name('how-it-works');

Route::get('/services', \App\Livewire\ServiceCategory\Browse::class)->name('services.browse');
Route::get('/services/{slug}', \App\Livewire\ServiceCategory\Show::class)->name('services.show');

Route::get('/provider/{slug}', \App\Livewire\Provider\Profile::class)->name('provider.profile');
