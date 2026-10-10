<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\ServiceArea;
use App\Models\ProviderService;
use Illuminate\Support\Facades\Hash;

$password = Hash::make('password123');

// Admin
$admin = User::updateOrCreate(
    ['email' => 'admin@kaazilink.com'],
    [
        'name' => 'Admin QA',
        'password' => $password,
        'role' => 'admin',
        'kyc_status' => 'approved',
        'email_verified_at' => now(),
    ]
);

// Approved Provider
$provider = User::updateOrCreate(
    ['email' => 'pro_samuel@kaazilink.com'],
    [
        'name' => 'Samuel Mwangi',
        'password' => $password,
        'role' => 'provider',
        'kyc_status' => 'approved',
        'email_verified_at' => now(),
        'id_number' => '28374619',
        'bio' => 'Experienced technician and home care specialist with 8 years of practice.',
    ]
);

// Area & Services for Provider
$area = ServiceArea::firstOrCreate(
    ['name' => 'Westlands'],
    ['latitude' => -1.2683, 'longitude' => 36.8111]
);
if (!$provider->serviceAreas()->where('service_area_id', $area->id)->exists()) {
    $provider->serviceAreas()->attach($area->id);
}

ProviderService::updateOrCreate(
    ['user_id' => $provider->id, 'service_slug' => 'house-cleaning'],
    ['price' => 2500.00, 'is_custom' => false]
);

// Client
$client = User::updateOrCreate(
    ['email' => 'client_mary@kaazilink.com'],
    [
        'name' => 'Mary Njeri',
        'password' => $password,
        'role' => 'client',
        'email_verified_at' => now(),
    ]
);

// KYC Onboarding Provider (for testing submission, approval, rejection)
$kycProvider = User::updateOrCreate(
    ['email' => 'kyc_test_user@kaazilink.com'],
    [
        'name' => 'Kipchoge Keino',
        'password' => $password,
        'role' => 'provider',
        'kyc_status' => 'unsubmitted',
        'email_verified_at' => now(),
        'id_number' => '38472910',
        'phone' => '0712345678',
        'bio' => 'Professional electrician with 5 years experience across Nairobi residential homes.',
    ]
);
// Ensure kycProvider has at least one service and one area
ProviderService::updateOrCreate(
    ['user_id' => $kycProvider->id, 'service_slug' => 'house-cleaning'],
    ['price' => 2200.00, 'is_custom' => false]
);
if (!$kycProvider->serviceAreas()->where('service_area_id', $area->id)->exists()) {
    $kycProvider->serviceAreas()->attach($area->id);
}
// Delete any existing submissions for kycProvider so they can submit fresh
$kycProvider->kycSubmissions()->delete();


echo "Test users ready:\n";
echo "Admin: admin@kaazilink.com / password123\n";
echo "Provider: pro_samuel@kaazilink.com / password123\n";
echo "Client: client_mary@kaazilink.com / password123\n";
echo "KYC Candidate: kyc_test_user@kaazilink.com / password123\n";
