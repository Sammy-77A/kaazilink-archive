<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProviderService;
use App\Models\Booking;

$services = ProviderService::whereIn('user_id', [4, 6])->get();
echo "Provider Services for 4 and 6:\n";
foreach ($services as $s) {
    echo "Provider {$s->user_id}: service_slug={$s->service_slug}, price=" . var_export($s->price, true) . "\n";
}

$recent = Booking::latest()->take(5)->get();
echo "\nRecent Bookings:\n";
foreach ($recent as $b) {
    echo "Booking #{$b->id}: client={$b->client_id}, provider={$b->provider_id}, service={$b->service_slug}, price=" . var_export($b->price, true) . ", status={$b->status}\n";
}
