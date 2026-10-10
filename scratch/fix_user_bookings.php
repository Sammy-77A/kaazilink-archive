<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;
use App\Models\ProviderService;

// Check user 12 bookings
$bookings = Booking::where('client_id', 12)->get();
echo "Found " . $bookings->count() . " bookings for user 12:\n";
foreach ($bookings as $b) {
    echo " - Booking #{$b->id}: {$b->service_slug}, Price: " . var_export($b->price, true) . ", Status: {$b->status}\n";
}

// Give test prices to null price bookings for user 12
foreach ($bookings as $b) {
    if (is_null($b->price) || $b->price <= 0) {
        $b->update(['price' => 10.00]); // 10 KES test amount for PayHero
        echo "Updated Booking #{$b->id} price to KES 10.00\n";
    }
}

// Also update provider services that have NULL price to a default price
$updatedServices = ProviderService::whereNull('price')->update(['price' => 1500.00]);
echo "Updated {$updatedServices} provider_services with default price 1500.00\n";
