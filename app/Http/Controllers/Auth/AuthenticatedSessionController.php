<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        
        // Redirect based on user type
        return match($user->user_type) {
            'admin' => redirect()->route('hr.dashboard')->with('success', 'Welcome Admin!'),
            'hr_manager' => redirect()->route('hr.dashboard')->with('success', 'Welcome ' . $user->name . '!'),
            'evaluator' => redirect()->route('evaluator.dashboard')->with('success', 'Welcome ' . $user->name . '! Department: ' . ($user->department ?? 'General')),
            'applicant' => $this->redirectApplicant($user),
            default => redirect('/'),
        };
    }

    /**
     * Redirect applicant based on profile completion
     */
    private function redirectApplicant($user): RedirectResponse
    {
        $applicant = $user->applicant;
        
        // No profile yet
        if (!$applicant) {
            return redirect()->route('applicant.profile.create')
                ->with('success', 'Welcome! Please complete your profile to apply for jobs.');
        }
        
        // Profile incomplete
        if (!$applicant->profile_completed) {
            if (!$applicant->educationHistories()->exists()) {
                return redirect()->route('applicant.education.create')
                    ->with('info', 'Welcome back! Please add your education history.');
            }
            // Auto-complete if education exists
            $applicant->update(['profile_completed' => true]);
        }
        
        return redirect()->route('applicant.dashboard')
            ->with('success', 'Welcome back, ' . $user->name . '!');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'You have been logged out.');
    }
}
