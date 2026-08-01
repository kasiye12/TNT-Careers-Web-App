<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        // Auto-verify and redirect
        if (!$request->user()->hasVerifiedEmail()) {
            $request->user()->markEmailAsVerified();
        }
        
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
