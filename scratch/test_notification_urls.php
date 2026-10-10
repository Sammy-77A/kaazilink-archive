<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\KycSubmission;

// 1. Setup personas
$admin = User::where('role', 'admin')->first();
$provider = User::where('role', 'provider')->where('kyc_status', 'approved')->first();
$client = User::where('role', 'client')->first();
$submission = KycSubmission::latest()->first();

echo "Admin ID: " . ($admin ? $admin->id : 'none') . "\n";
echo "Provider ID: " . ($provider ? $provider->id : 'none') . "\n";
echo "Client ID: " . ($client ? $client->id : 'none') . "\n";
echo "Submission ID: " . ($submission ? $submission->id : 'none') . "\n\n";

$routesToTest = [
    [
        'name' => 'Services Directory (/services)',
        'url' => '/services',
        'as' => null, // public
    ],
    [
        'name' => 'Provider Public Profile (/provider/' . ($provider ? $provider->id : '15') . ')',
        'url' => '/provider/' . ($provider ? $provider->id : '15'),
        'as' => null, // public
    ],
    [
        'name' => 'Provider Hub (/provider/dashboard)',
        'url' => '/provider/dashboard',
        'as' => $provider,
    ],
    [
        'name' => 'Client Bookings Dashboard (/dashboard/bookings)',
        'url' => '/dashboard/bookings',
        'as' => $client,
    ],
    [
        'name' => 'Provider Onboarding (/provider/onboarding)',
        'url' => '/provider/onboarding',
        'as' => $provider,
    ],
    [
        'name' => 'Admin KYC Review (/admin/kyc/' . ($submission ? $submission->id : '1') . ')',
        'url' => '/admin/kyc/' . ($submission ? $submission->id : '1'),
        'as' => $admin,
    ],
];

foreach ($routesToTest as $r) {
    if ($r['as']) {
        \Illuminate\Support\Facades\Auth::login($r['as']);
    } else {
        \Illuminate\Support\Facades\Auth::logout();
    }

    $request = Request::create($r['url'], 'GET');
    $response = $kernel->handle($request);
    $status = $response->getStatusCode();
    
    echo "URL: {$r['url']}\n";
    echo "ROUTE PURPOSE: {$r['name']}\n";
    echo "HTTP STATUS: {$status} " . ($status === 200 ? "OK" : "REDIRECT/ERROR") . "\n";
    if ($status !== 200) {
        echo "REDIRECT TARGET: " . $response->headers->get('Location') . "\n";
    }
    echo "----------------------------------------\n";
    
    $kernel->terminate($request, $response);
}
