<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = \App\Models\User::where('email', 'kyc_test_user@kaazilink.com')->first();
echo "User: " . ($u ? "ID {$u->id}, status {$u->kyc_status}, role {$u->role}" : "NOT FOUND") . PHP_EOL;

$sub = \App\Models\KycSubmission::latest()->first();
echo "Latest submission: " . ($sub ? "ID {$sub->id}, User {$sub->user_id}, Status {$sub->status}" : "NONE") . PHP_EOL;
