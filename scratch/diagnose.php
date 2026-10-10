<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Models\ServiceArea;
use App\Data\ServiceDirectory;

echo "1. User::where('role', 'provider')->count(): " . User::where('role', 'provider')->count() . PHP_EOL;

echo "2. User::where('role', 'provider')->where('kyc_status', 'approved')->count(): " . User::where('role', 'provider')->where('kyc_status', 'approved')->count() . PHP_EOL;

echo "3. User::where('role', 'provider')->whereHas('kycSubmissions', function(\$q) { \$q->where('status', 'approved'); })->count(): " . User::where('role', 'provider')->whereHas('kycSubmissions', function($q) { $q->where('status', 'approved'); })->count() . PHP_EOL;

// Check all categories and how many providers match their services in ServiceDirectory
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

    echo "Category [" . $category['slug'] . "] service slugs count: " . count($serviceSlugs) . PHP_EOL;
    echo "Matching providers count: " . $query->count() . PHP_EOL;
}
