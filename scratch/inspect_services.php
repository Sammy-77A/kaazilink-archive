<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Data\ServiceDirectory;

echo "=== 1. CHECKING ServiceDirectory ===\n";
$directoryServices = collect(ServiceDirectory::getServices());
echo "Total services in ServiceDirectory: " . $directoryServices->count() . "\n";
$solar = $directoryServices->filter(function($s) {
    return stripos($s['name'], 'solar') !== false || stripos($s['name'], 'cctv') !== false || stripos($s['slug'], 'solar') !== false;
});
echo "Matching 'solar' or 'cctv' in ServiceDirectory:\n";
print_r($solar->values()->toArray());

echo "\n=== 2. CHECKING services TABLE (IF EXISTS) ===\n";
if (\Illuminate\Support\Facades\Schema::hasTable('services')) {
    $services = DB::table('services')->get();
    echo "Total records in services table: " . $services->count() . "\n";
    print_r($services->toArray());
} else {
    echo "Note: The database does NOT have a 'services' table. Services are defined statically in App\\Data\\ServiceDirectory.\n";
}

echo "\n=== 3. CHECKING provider_services TABLE ===\n";
if (\Illuminate\Support\Facades\Schema::hasTable('provider_services')) {
    $ps = DB::table('provider_services')->get();
    echo "Total records in provider_services: " . $ps->count() . "\n";
    print_r($ps->toArray());
}

echo "\n=== 4. CHECKING bookings TABLE (service_slugs used) ===\n";
$bookings = DB::table('bookings')->select('id', 'client_id', 'provider_id', 'service_slug', 'price', 'status')->get();
print_r($bookings->toArray());
