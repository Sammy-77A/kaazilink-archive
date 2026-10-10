<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

foreach (['pat@kz.com', 'emm@kz.com', 'eth@kz.com', 'vic@kz.com'] as $email) {
    $provider = User::where('email', $email)->first();
    echo "\nTesting Provider: {$provider->name} ({$provider->email})" . PHP_EOL;
    
    $services = $provider->providerServices->map(function($ps) {
        return [
            'value' => $ps->service_slug,
            'label' => $ps->display_name . ' (' . $ps->formatted_price . ')',
        ];
    })->toArray();

    $areas = $provider->serviceAreas->map(function($area) {
        return ['value' => $area->id, 'label' => $area->name];
    })->toArray();

    echo "Services Count: " . count($services) . PHP_EOL;
    echo "Areas Count: " . count($areas) . PHP_EOL;
    foreach ($areas as $a) {
        echo " - Area ID: {$a['value']}, Label: {$a['label']}" . PHP_EOL;
    }
}
