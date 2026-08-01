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
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Redirect based on user type
        $user = Auth::user();
        
        if ($user->user_type === 'applicant') {
            if (!$user->applicant || !$user->applicant->profile_completed) {
                return redirect()->route('applicant.profile.create');
            }
            return redirect()->route('applicant.dashboard');
        }
        
        if (in_array($user->user_type, ['admin', 'hr_manager', 'evaluator'])) {
            return redirect()->route('hr.dashboard');
        }

        return redirect('/');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
