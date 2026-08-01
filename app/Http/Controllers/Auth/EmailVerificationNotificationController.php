<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        // Auto-verify instead of sending email
        if (!$request->user()->hasVerifiedEmail()) {
            $request->user()->markEmailAsVerified();
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
