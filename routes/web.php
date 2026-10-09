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
    
    // Become a Provider
    Route::post('/provider/become', function () {
        $user = Auth::user();
        if ($user->role === 'client') {
            $user->update(['role' => 'provider', 'kyc_status' => 'pending']);
        }
        return redirect()->route('kyc.submit');
    })->name('provider.become');

    // Provider KYC Submit
    Route::get('/kyc/submit', \App\Livewire\Provider\KycSubmit::class)->name('kyc.submit');
    
    // Protected Document Access
    Route::get('/kyc/document/{path}', [\App\Http\Controllers\KycDocumentController::class, 'show'])
        ->where('path', '.*')
        ->name('kyc.document');

    // Provider Service Areas
    Route::get('/provider/service-areas', \App\Livewire\Provider\ServiceAreasManager::class)->name('provider.service-areas');
    Route::get('/provider/services', \App\Livewire\Provider\ProviderServicesManager::class)->name('provider.services');
    // Bookings Dashboard
    Route::get('/dashboard/bookings', \App\Livewire\Dashboard\Bookings::class)->name('dashboard.bookings');
});

// Admin Routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', \App\Livewire\Admin\Dashboard::class)->name('dashboard');
    Route::get('/kyc', \App\Livewire\Admin\Kyc\Index::class)->name('kyc.index');
    Route::get('/kyc/{submission}', \App\Livewire\Admin\Kyc\Show::class)->name('kyc.show');
    Route::get('/settings', \App\Livewire\Admin\Settings\Index::class)->name('settings.index');
    Route::get('/metrics', \App\Livewire\Admin\Metrics\Index::class)->name('metrics.index');
});

// Phase 1 "Sockets": Foundational Livewire Component Routes
// These currently use the stubbed components in App\Livewire

Route::get('/', \App\Livewire\Pages\Home::class)->name('home');
Route::get('/how-it-works', \App\Livewire\Pages\HowItWorks::class)->name('how-it-works');

Route::get('/services', \App\Livewire\ServiceCategory\Browse::class)->name('services.browse');
Route::get('/services/{slug}', \App\Livewire\ServiceCategory\Show::class)->name('services.show');

Route::get('/provider/{slug}', \App\Livewire\Provider\Profile::class)->name('provider.profile');
