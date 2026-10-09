<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$user = App\Models\User::where('email', 'samuendubi325@gmail.com')->first();
$cat = App\Models\ServiceCategory::where('slug', 'plumbing')->first();
if(!$cat) $cat = App\Models\ServiceCategory::create(['name' => 'Plumbing', 'slug' => 'plumbing']);
$user->serviceCategories()->sync([$cat->id]);
echo "Linked\n";
