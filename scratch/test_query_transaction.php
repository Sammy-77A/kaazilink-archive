<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Payment;
use App\Services\PaymentManager;

$payment = Payment::find(2);
$manager = app(PaymentManager::class);
$driver = $manager->driver('payhero');

$payment = Payment::find(2);
echo "Payment reference: " . $payment->reference . "\n";
echo "CheckoutRequestID: " . $payment->checkout_request_id . "\n";

require_once base_path('vendor/payherokenya/payhero-php/ph-class.php');
$api = new \PayHeroAPI(config('payments.gateways.payhero.api_username'), config('payments.gateways.payhero.api_password'));
$resRef = $api->getTransactionStatus($payment->reference);
echo "getTransactionStatus(reference):\n" . $resRef . "\n";

$resCheck = $api->getTransactionStatus($payment->checkout_request_id);
echo "getTransactionStatus(checkout_request_id):\n" . $resCheck . "\n";



