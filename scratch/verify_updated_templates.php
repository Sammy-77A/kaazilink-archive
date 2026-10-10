<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Booking;
use App\Models\Review;
use App\Models\KycSubmission;
use App\Notifications\NewBookingRequestNotification;
use App\Notifications\BookingCompletedNotification;
use App\Notifications\KycPendingReviewNotification;
use App\Notifications\KycApprovedNotification;
use App\Notifications\KycRejectedNotification;
use App\Notifications\NewReviewNotification;
use App\Notifications\WelcomeNotification;

// Clear log
file_put_contents(storage_path('logs/laravel.log'), '');

$provider = User::where('role', 'provider')->first();
$client = User::where('role', 'client')->first();
$admin = User::where('role', 'admin')->first();
$submission = KycSubmission::latest()->first();

// 1. New Booking Request
$booking = Booking::where('client_id', $client->id)->where('provider_id', $provider->id)->first();
if (!$booking) {
    $booking = Booking::create([
        'client_id' => $client->id,
        'provider_id' => $provider->id,
        'service_slug' => 'house-cleaning',
        'scheduled_at' => now()->addDays(2),
        'status' => 'requested',
    ]);
}
$provider->notify(new NewBookingRequestNotification($booking));

// 2. Booking Completed (Both directions)
$client->notify(new BookingCompletedNotification($booking, 'provider'));
$provider->notify(new BookingCompletedNotification($booking, 'client'));

// 3. KYC Pending Review
$admin->notify(new KycPendingReviewNotification($submission));

// 4. KYC Approved
$provider->notify(new KycApprovedNotification($submission));

// 5. KYC Rejected
$provider->notify(new KycRejectedNotification($submission, 'Your National ID copy is blurry and the expiration date cannot be verified.'));

// 6. Review
$review = Review::first();
if ($review) {
    $provider->notify(new NewReviewNotification($review));
}

// 7. Welcome
$client->notify(new WelcomeNotification());

echo file_get_contents(storage_path('logs/laravel.log'));
