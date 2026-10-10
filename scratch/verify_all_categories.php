<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Data\ServiceDirectory;

echo "========================================================" . PHP_EOL;
echo "=== LOCAL VERIFICATION: ALL 6 SERVICE CATEGORIES ===" . PHP_EOL;
echo "========================================================" . PHP_EOL;

echo "Total Role 'provider' users: " . User::where('role', 'provider')->count() . PHP_EOL;
echo "KYC Approved 'provider' users: " . User::where('role', 'provider')->where('kyc_status', 'approved')->count() . PHP_EOL;

foreach (ServiceDirectory::getCategories() as $category) {
    $serviceSlugs = collect(ServiceDirectory::getServices())
        ->where('category_id', $category['id'])
        ->pluck('slug')
        ->toArray();

    $query = User::approvedProvider()->where(function($q) use ($serviceSlugs) {
        $q->whereHas('providerServices', function($sub) use ($serviceSlugs) {
            $sub->whereIn('service_slug', $serviceSlugs);
        });
    });

    $count = $query->count();
    echo "\nCategory [ID: {$category['id']} | Name: {$category['name']} ({$category['slug']})]" . PHP_EOL;
    echo " -> Matching Visible Providers Count: {$count}" . PHP_EOL;
    
    $sampleProviders = $query->with('serviceAreas')->take(5)->get();
    foreach ($sampleProviders as $p) {
        $areasCount = $p->serviceAreas->count();
        echo "    * Provider: {$p->name} ({$p->email}) | Serves {$areasCount} areas" . PHP_EOL;
    }
}

echo "\n=== PROVIDERS WITH ZERO SERVICE AREAS CHECK ===" . PHP_EOL;
$zeroAreaProviders = User::where('role', 'provider')->doesntHave('serviceAreas')->count();
echo "Providers with 0 service areas: {$zeroAreaProviders}" . PHP_EOL;
