<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\VerifyEmail;
use App\Http\Controllers\Auth\GoogleAuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;

// Guest Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
    
    // Google OAuth
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', VerifyEmail::class)->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/');
    })->middleware('signed')->name('verification.verify');

    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

// Phase 1 "Sockets": Foundational Livewire Component Routes
// These currently use the stubbed components in App\Livewire

Route::get('/', \App\Livewire\Pages\Home::class)->name('home');
Route::get('/how-it-works', \App\Livewire\Pages\HowItWorks::class)->name('how-it-works');

Route::get('/services', \App\Livewire\ServiceCategory\Browse::class)->name('services.browse');
Route::get('/services/{slug}', \App\Livewire\ServiceCategory\Show::class)->name('services.show');

Route::get('/provider/{slug}', \App\Livewire\Provider\Profile::class)->name('provider.profile');
