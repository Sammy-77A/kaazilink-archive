<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// 4c: A check confirming the protected document route returns 403 when accessed by non-admin, non-owner
$user = App\Models\User::factory()->create(['role' => 'client']);
Illuminate\Support\Facades\Auth::login($user);

$request = Illuminate\Http\Request::create('/kyc/document/some/path.jpg', 'GET');
$response = $kernel->handle($request);
echo "4c: GET /kyc/document/some/path.jpg as non-owner -> HTTP " . $response->getStatusCode() . "\n";

Illuminate\Support\Facades\Auth::logout();
$user->delete();

// 4d: A real request to the unapproved test provider's direct profile URL showing HTTP 404
$unapproved = App\Models\User::factory()->create(['role' => 'provider', 'kyc_status' => 'pending']);
$request2 = Illuminate\Http\Request::create('/provider/' . $unapproved->id, 'GET');
$response2 = $kernel->handle($request2);
echo "4d: GET /provider/{$unapproved->id} (unapproved provider) -> HTTP " . $response2->getStatusCode() . "\n";
$unapproved->delete();
