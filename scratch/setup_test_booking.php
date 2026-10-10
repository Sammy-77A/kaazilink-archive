<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\Booking;
use Illuminate\Support\Facades\Hash;

// Ensure Mary Njeri exists and has password 'password'
$client = User::updateOrCreate(
    ['email' => 'client_mary@kaazilink.com'],
    [
        'name' => 'Mary Njeri',
        'password' => Hash::make('password'),
        'role' => 'client',
        'email_verified_at' => now(),
        'phone' => '0712345678',
    ]
);

$provider = User::find(15) ?: User::where('role', 'provider')->first();

// Create or update a test booking ready for payment (e.g. price 10 KES or 100 KES)
$booking = Booking::create([
    'client_id' => $client->id,
    'provider_id' => $provider->id,
    'status' => 'requested',
    'scheduled_at' => now()->addDays(2),
    'notes' => 'Testing PayHero M-PESA integration',
    'service_slug' => 'house-cleaning',
    'price' => 10.00, // Small test amount for PayHero
]);

echo "Created Booking #{$booking->id} for Client {$client->email} (password: password) with Provider {$provider->name}, Price: KES {$booking->price}\n";
