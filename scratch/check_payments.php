<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Payment;

$payments = Payment::latest()->take(10)->get();
echo "Found " . $payments->count() . " payments:\n";
foreach ($payments as $p) {
    echo "ID: {$p->id}, Booking: {$p->booking_id}, Gateway: {$p->gateway}, Amount: {$p->amount}, Phone: {$p->phone}, Status: {$p->status}, Error: {$p->error_message}, CheckoutID: {$p->checkout_request_id}\n";
    if ($p->callback_payload) {
        echo " - Callback Payload: " . json_encode($p->callback_payload) . "\n";
    }
}
