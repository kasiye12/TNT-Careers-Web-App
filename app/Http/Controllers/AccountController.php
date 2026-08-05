<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AccountController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    /**
     * Show account settings page
     */
    public function settings()
    {
        $user = Auth::user();
        return view('account.profile-settings');
    }

    /**
     * Update account email
     */
    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'current_password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();
        $user->email = $request->email;
        $user->email_verified_at = null;
        $user->save();

        // Send verification email
        $user->sendEmailVerificationNotification();

        return back()->with('success', 'Email updated successfully. Please verify your new email address.');
    }

    /**
     * Update account phone
     */
    public function updatePhone(Request $request)
    {
        $request->validate([
            'phone' => ['required', 'string', 'max:20', 'unique:users,phone,' . Auth::id(), 'regex:/^(\+251|0)[1-9]\d{8}$/'],
            'current_password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();
        
        // Format phone number
        $phone = $request->phone;
        if (str_starts_with($phone, '0')) {
            $phone = '+251' . substr($phone, 1);
        }
        
        $user->phone = $phone;
        $user->save();

        return back()->with('success', 'Phone number updated successfully.');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();

        // Logout other devices (optional)
        // Auth::logoutOtherDevices($request->password);

        return back()->with('success', 'Password changed successfully.');
    }

    /**
     * Delete account
     */
    public function deleteAccount(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
            'confirmation' => ['required', 'string', 'in:DELETE MY ACCOUNT'],
        ]);

        $user = Auth::user();
        
        // Logout user
        Auth::logout();
        
        // Delete user and all related data (cascade)
        $user->delete();
        
        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Your account has been permanently deleted.');
    }

    /**
     * Show notification preferences
     */
    public function notifications()
    {
        $user = Auth::user();
        return view('account.notifications', compact('user'));
    }

    /**
     * Update notification preferences
     */
    public function updateNotifications(Request $request)
    {
        $validated = $request->validate([
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'application_updates' => 'boolean',
            'interview_reminders' => 'boolean',
            'job_alerts' => 'boolean',
        ]);

        $user = Auth::user();
        
        // Store preferences in a settings table or JSON column
        // For now, we'll store in a simple way
        $user->notification_preferences = json_encode($validated);
        $user->save();

        return back()->with('success', 'Notification preferences updated successfully.');
    }

    /**
     * Show login history / active sessions
     */
    public function sessions()
    {
        $user = Auth::user();
        $sessions = $user->sessions()->orderBy('last_activity', 'desc')->get();
        
        return view('account.sessions', compact('sessions'));
    }

    /**
     * Terminate a specific session
     */
    public function terminateSession(Request $request, $sessionId)
    {
        $user = Auth::user();
        
        // Don't allow terminating current session
        if ($sessionId === $request->session()->getId()) {
            return back()->with('error', 'You cannot terminate your current session.');
        }

        $user->sessions()->where('id', $sessionId)->delete();

        return back()->with('success', 'Session terminated successfully.');
    }

    /**
     * Terminate all other sessions
     */
    public function terminateAllSessions(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();
        $currentSessionId = $request->session()->getId();
        
        // Delete all sessions except current
        $user->sessions()->where('id', '!=', $currentSessionId)->delete();

        return back()->with('success', 'All other sessions have been terminated.');
    }
}
