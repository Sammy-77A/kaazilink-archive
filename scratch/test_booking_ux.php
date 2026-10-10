<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Booking;
use App\Models\ServiceArea;
use Livewire\Livewire;
use App\Livewire\Booking\CreateModal;
use Illuminate\Support\Facades\Auth;

echo "=== TESTING BOOKING MODAL UX & EVENTS ===" . PHP_EOL;

$client = User::where('role', 'client')->first();
$provider = User::where('role', 'provider')->where('kyc_status', 'approved')->has('providerServices')->first();
$service = $provider->providerServices->first();
$area = $provider->serviceAreas->first();

echo "Client: {$client->name} ({$client->email})" . PHP_EOL;
echo "Provider: {$provider->name} ({$provider->email})" . PHP_EOL;
echo "Service: {$service->service_slug}, Price: {$service->price}" . PHP_EOL;
echo "Area: {$area->name} (ID: {$area->id})" . PHP_EOL;

Auth::login($client);

// 1. Test Successful Booking Flow
$test = Livewire::test(CreateModal::class)
    ->call('openModal', providerId: $provider->id, serviceSlug: $service->service_slug)
    ->set('serviceAreaId', $area->id)
    ->call('submit');

$test->assertDispatched('booking-success-toast');
$test->assertDispatched('bookingCreated');
$test->assertDispatched('schedule-modal-close');
$test->assertSet('successMessage', 'Booking request sent! You can track it in My Bookings.');

$booking = Booking::where('client_id', $client->id)->where('provider_id', $provider->id)->latest()->first();
echo "\n1. SUCCESS FLOW TEST PASSED!" . PHP_EOL;
echo "Created Booking ID: {$booking->id}, Status: {$booking->status}, Price: {$booking->price}" . PHP_EOL;

// 2. Test Failure Flow (invalid service area)
$testFail = Livewire::test(CreateModal::class)
    ->call('openModal', providerId: $provider->id, serviceSlug: $service->service_slug)
    ->set('serviceAreaId', 999999) // Invalid area ID
    ->call('submit');

$testFail->assertSet('errorMessage', 'Something went wrong. Please try again.');
$testFail->assertNotDispatched('booking-success-toast');
$testFail->assertNotDispatched('schedule-modal-close');
echo "\n2. FAILURE FLOW TEST PASSED!" . PHP_EOL;
echo "ErrorMessage Set: " . $testFail->get('errorMessage') . PHP_EOL;
