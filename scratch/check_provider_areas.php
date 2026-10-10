<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\ServiceArea;

echo "=== SERVICE AREAS IN DATABASE ===" . PHP_EOL;
echo "Total Service Areas in DB: " . ServiceArea::count() . PHP_EOL;

echo "\n=== PATRICK MUTISO (pat@kz.com) ===" . PHP_EOL;
$pat = User::where('email', 'pat@kz.com')->first();
if ($pat) {
    echo "ID: {$pat->id}, Name: {$pat->name}" . PHP_EOL;
    echo "Attached Service Areas Count: " . $pat->serviceAreas()->count() . PHP_EOL;
    foreach ($pat->serviceAreas as $area) {
        echo " - Area ID: {$area->id}, Name: {$area->name}, Lat: {$area->latitude}, Lng: {$area->longitude}" . PHP_EOL;
    }
} else {
    echo "Patrick Mutiso not found!" . PHP_EOL;
}

echo "\n=== EMMANUEL KIBET (emm@kz.com) ===" . PHP_EOL;
$emm = User::where('email', 'emm@kz.com')->first();
if ($emm) {
    echo "ID: {$emm->id}, Name: {$emm->name}" . PHP_EOL;
    echo "Attached Service Areas Count: " . $emm->serviceAreas()->count() . PHP_EOL;
    foreach ($emm->serviceAreas as $area) {
        echo " - Area ID: {$area->id}, Name: {$area->name}, Lat: {$area->latitude}, Lng: {$area->longitude}" . PHP_EOL;
    }
} else {
    echo "Emmanuel Kibet not found!" . PHP_EOL;
}

echo "\n=== ALL 18 SEEDED PROVIDERS SERVICE AREA COUNTS ===" . PHP_EOL;
$providers = User::where('role', 'provider')->get();
foreach ($providers as $p) {
    echo "Email: {$p->email} | Name: {$p->name} | Areas Count: " . $p->serviceAreas()->count() . PHP_EOL;
}
