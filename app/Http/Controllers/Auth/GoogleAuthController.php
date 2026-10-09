<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Find existing user by email
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // If user exists, link Google ID if not already linked
                if (!$user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar' => $googleUser->getAvatar(),
                    ]);
                }
                
                // If they hadn't verified their email yet (e.g. they registered via email previously but didn't click link), 
                // Google auth confirms email ownership.
                if (!$user->hasVerifiedEmail()) {
                    $user->markEmailAsVerified();
                }
            } else {
                // User does not exist, create new
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
                $user->markEmailAsVerified();
            }

            Auth::login($user, remember: true);
            request()->session()->regenerate();

            return redirect()->intended('/');
            
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Google Auth Failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return redirect('/login')->with('error', 'Authentication failed: ' . $e->getMessage());
        }
    }
}
