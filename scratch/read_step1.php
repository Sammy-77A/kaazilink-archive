<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Data\ServiceDirectory;
use App\Models\ServiceArea;
use App\Models\User;

echo "=== 1. SERVICE DIRECTORY SLUGS BY CATEGORY ===" . PHP_EOL;
$categories = ServiceDirectory::getCategories();
$services = ServiceDirectory::getServices();

foreach ($categories as $cat) {
    echo "\nCategory [ID: {$cat['id']} | Slug: {$cat['slug']} | Name: {$cat['name']}]" . PHP_EOL;
    $catServices = collect($services)->where('category_id', $cat['id']);
    foreach ($catServices as $s) {
        echo "  - Slug: {$s['slug']} | Name: {$s['name']} | Price Range: {$s['price_range']}" . PHP_EOL;
    }
}

echo "\n=== 3. SERVICE AREA SEEDER COUNT ===" . PHP_EOL;
echo "Total Service Areas in DB: " . ServiceArea::count() . PHP_EOL;

echo "\n=== 4. SPOT-CHECK PIVOT ROWS FOR 2 EXISTING PROVIDERS ===" . PHP_EOL;
$spot1 = User::where('email', 'eth@kz.com')->first();
if ($spot1) {
    echo "Provider: {$spot1->name} ({$spot1->email})" . PHP_EOL;
    echo "Pivot serviceAreas count: " . $spot1->serviceAreas()->count() . PHP_EOL;
    foreach ($spot1->serviceAreas as $a) {
        echo "  - Area ID: {$a->id}, Name: {$a->name}" . PHP_EOL;
    }
}

$spot2 = User::where('email', 'jam@kz.com')->first();
if ($spot2) {
    echo "\nProvider: {$spot2->name} ({$spot2->email})" . PHP_EOL;
    echo "Pivot serviceAreas count: " . $spot2->serviceAreas()->count() . PHP_EOL;
    foreach ($spot2->serviceAreas as $a) {
        echo "  - Area ID: {$a->id}, Name: {$a->name}" . PHP_EOL;
    }
}
