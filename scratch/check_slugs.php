<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Data\ServiceDirectory;
use App\Models\ProviderService;
use App\Models\User;

$dirServices = collect(ServiceDirectory::getServices())->pluck('slug')->toArray();

echo "Valid ServiceDirectory Slugs:\n" . implode("\n", $dirServices) . "\n\n";

$providerServices = ProviderService::with('user')->get();
echo "Provider Services in DB:\n";
foreach ($providerServices as $ps) {
    $valid = in_array($ps->service_slug, $dirServices) ? 'VALID' : 'INVALID (Not in directory)';
    echo "User: {$ps->user->name} | Slug: {$ps->service_slug} | Status: {$valid}\n";
}
