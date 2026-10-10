<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Booking;
use App\Models\Payment;

$client = User::where('role', 'client')->first();
$booking = Booking::where('client_id', $client->id)->first();

$testRef = 'KZL-LIVE-TEST-' . time();
$payment = Payment::create([
    'booking_id' => $booking->id,
    'user_id' => $client->id,
    'gateway' => 'payhero',
    'amount' => 1500.00,
    'phone' => '254712345678',
    'status' => 'initiated',
    'reference' => $testRef,
]);

echo "Created test payment with reference: {$testRef}\n";

// Send HTTP POST webhook to live app
$payload = json_encode([
    'response' => [
        'status' => 'Success',
        'amount' => 1500,
        'phone' => '254712345678',
        'external_reference' => $testRef,
        'mpesa_reference' => 'TEST_RECEIPT_' . rand(1000, 9999),
        'channel_id' => 234,
    ],
    'status' => 'Success',
]);

$ch = curl_init('http://127.0.0.1:8000/api/payments/callback/payhero');
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json', 'Accept:application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: {$httpCode}\n";
echo "Response: {$response}\n";

$payment->refresh();
echo "Updated Payment Status: {$payment->status}\n";
echo "Updated Transaction ID: {$payment->transaction_id}\n";
echo "Paid At: {$payment->paid_at}\n";
