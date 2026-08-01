<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        // Force exact redirect URI
        config(['services.google.redirect' => 'http://localhost:8000/auth/google/callback']);
        
        return Socialite::driver('google')
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function callback()
    {
        try {
            // Force same redirect URI for callback
            config(['services.google.redirect' => 'http://localhost:8000/auth/google/callback']);
            
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            $user = User::where('google_id', $googleUser->id)->first();
            
            if (!$user) {
                $user = User::where('email', $googleUser->email)->first();
                
                if ($user) {
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                    ]);
                } else {
                    $user = User::create([
                        'name' => $googleUser->name,
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar,
                        'phone' => '+251900000000',
                        'password' => Hash::make(uniqid()),
                        'user_type' => 'applicant',
                        'status' => 'active',
                        'email_verified_at' => now(),
                    ]);
                    $user->assignRole('applicant');
                }
            }
            
            Auth::login($user);
            
            if ($user->user_type === 'applicant') {
                if (!$user->applicant || !$user->applicant->profile_completed) {
                    return redirect()->route('applicant.profile.create')
                        ->with('success', 'Welcome ' . $user->name . '! Please complete your profile.');
                }
                return redirect()->route('applicant.dashboard');
            }
            
            return redirect()->route('hr.dashboard');
            
        } catch (\Exception $e) {
            \Log::error('Google Auth Error: ' . $e->getMessage());
            return redirect()->route('login')
                ->with('error', 'Google sign in failed. Error: ' . $e->getMessage());
        }
    }
}
