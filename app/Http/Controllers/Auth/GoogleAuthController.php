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
        config(['services.google.redirect' => config('services.google.redirect')]);
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            config(['services.google.redirect' => config('services.google.redirect')]);
            
            $googleUser = Socialite::driver('google')->stateless()->user();
            
            // Check if user exists by google_id
            $user = User::where('google_id', $googleUser->id)->first();
            
            if (!$user) {
                // Check if user exists by email
                $user = User::where('email', $googleUser->email)->first();
                
                if ($user) {
                    // Link Google account to existing user
                    $user->update([
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar ?? null,
                    ]);
                } else {
                    // Create new user with unique phone number
                    $phone = '+251' . rand(900000000, 999999999);
                    
                    // Make sure phone is unique
                    while (User::where('phone', $phone)->exists()) {
                        $phone = '+251' . rand(900000000, 999999999);
                    }
                    
                    $user = User::create([
                        'name' => $googleUser->name ?? 'Google User',
                        'email' => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'avatar' => $googleUser->avatar ?? null,
                        'phone' => $phone,
                        'password' => Hash::make(uniqid() . rand(1000, 9999)),
                        'user_type' => 'applicant',
                        'status' => 'active',
                        'email_verified_at' => now(),
                    ]);
                    
                    // Assign applicant role if exists
                    if (method_exists($user, 'assignRole')) {
                        $user->assignRole('applicant');
                    }
                }
            }
            
            // Update avatar if changed
            if ($googleUser->avatar && $user->avatar !== $googleUser->avatar) {
                $user->update(['avatar' => $googleUser->avatar]);
            }
            
            // Login
            Auth::login($user);
            
            // Redirect based on user type
            if ($user->user_type === 'applicant') {
                if (!$user->applicant || !$user->applicant->profile_completed) {
                    return redirect()->route('applicant.profile.create')
                        ->with('success', 'Welcome ' . $user->name . '! Please complete your profile to apply for jobs.');
                }
                return redirect()->route('applicant.dashboard')
                    ->with('success', 'Welcome back, ' . $user->name . '!');
            }
            
            return redirect()->route('hr.dashboard')
                ->with('success', 'Welcome back, ' . $user->name . '!');
            
        } catch (\Exception $e) {
            \Log::error('Google Auth Error: ' . $e->getMessage());
            
            return redirect()->route('login')
                ->with('error', 'Google sign in failed. Please try again or use email/password.');
        }
    }
}
