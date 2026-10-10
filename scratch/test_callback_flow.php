<?php

$url = 'https://kacey-newsiest-decennially.ngrok-free.dev/api/payments/callback/payhero';

// Simulated cancellation payload matching M-PESA / PayHero
$payload = [
    'status' => 'Failed',
    'response' => [
        'MerchantRequestID' => '12345-67890',
        'CheckoutRequestID' => 'ws_CO_21092026021344466790370094',
        'ResultCode' => 1032,
        'ResultDesc' => 'Request cancelled by user',
        'external_reference' => 'KZL-6AB068B095550',
        'status' => 'Failed',
    ]
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json',
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Code: " . $httpCode . "\n";
echo "Response: " . $response . "\n";
