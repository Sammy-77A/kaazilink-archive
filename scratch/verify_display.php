<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Data\ServiceDirectory;

echo "=== LOCAL PROVIDER DISPLAY VERIFICATION ===" . PHP_EOL;

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

    echo "Category: {$category['name']} ({$category['slug']}) -> " . $query->count() . " matching providers visible." . PHP_EOL;
}
