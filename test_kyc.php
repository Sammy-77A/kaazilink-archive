<?php
$user = App\Models\User::factory()->create(['role'=>'provider', 'kyc_status'=>'pending']);
$sub = App\Models\KycSubmission::create([
    'user_id' => $user->id,
    'id_front_path' => 'foo',
    'id_back_path' => 'bar',
    'kra_pin_path' => 'baz',
    'status' => 'pending'
]);

echo "\n--- INITIAL SUBMISSION ---\n";
echo App\Models\KycSubmission::where('user_id', $user->id)->first()->toJson(JSON_PRETTY_PRINT);

$sub->update(['status'=>'rejected', 'rejection_reason'=>'Blurry ID']);
echo "\n\n--- AFTER REJECTION ---\n";
echo App\Models\KycSubmission::where('user_id', $user->id)->first()->toJson(JSON_PRETTY_PRINT);

$sub->update(['status'=>'pending', 'rejection_reason'=>null]);
echo "\n\n--- AFTER RESUBMISSION ---\n";
echo App\Models\KycSubmission::where('user_id', $user->id)->first()->toJson(JSON_PRETTY_PRINT);

$sub->update(['status'=>'approved']);
echo "\n\n--- AFTER APPROVAL ---\n";
echo App\Models\KycSubmission::where('user_id', $user->id)->first()->toJson(JSON_PRETTY_PRINT);

$user->delete();
echo "\n";
