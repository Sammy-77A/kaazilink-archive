<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Gateway: " . config('payments.default') . PHP_EOL;
echo "PayHero Callback URL: " . config('payments.gateways.payhero.callback_url') . PHP_EOL;
echo "PayHero Base URL: " . config('payments.gateways.payhero.base_url') . PHP_EOL;
echo "PayHero Channel ID: " . config('payments.gateways.payhero.channel_id') . PHP_EOL;
