<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::firstOrCreate(
    ['email' => 'samuendubi325@gmail.com'],
    ['name' => 'Test Provider', 'password' => bcrypt('523jtlLL5')]
);
$user->update(['role' => 'provider', 'kyc_status' => 'approved']);
echo "User setup complete\n";
