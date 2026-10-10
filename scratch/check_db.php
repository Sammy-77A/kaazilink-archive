<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Booking;

echo "Users: " . User::count() . PHP_EOL;
echo "Bookings: " . Booking::count() . PHP_EOL;

$clients = User::where('role', 'client')->get(['id', 'name', 'email']);
echo "Clients:\n";
foreach ($clients as $c) {
    echo " - ID: {$c->id}, Name: {$c->name}, Email: {$c->email}\n";
}

$providers = User::where('role', 'provider')->get(['id', 'name', 'email', 'status']);
echo "Providers:\n";
foreach ($providers as $p) {
    echo " - ID: {$p->id}, Name: {$p->name}, Status: {$p->status}\n";
}

$bookings = Booking::with(['client', 'provider'])->latest()->take(5)->get();
echo "Recent Bookings:\n";
foreach ($bookings as $b) {
    echo " - ID: {$b->id}, Client: {$b->client?->name}, Provider: {$b->provider?->name}, Price: {$b->price}, Status: {$b->status}\n";
}
