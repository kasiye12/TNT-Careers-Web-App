<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:20', 'unique:'.User::class, 'regex:/^(\+251|0)[1-9]\d{8}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'phone.regex' => 'Please enter a valid Ethiopian phone number (+2519XXXXXXXX or 09XXXXXXXX)',
        ]);

        $phone = $request->phone;
        if (str_starts_with($phone, '0')) {
            $phone = '+251' . substr($phone, 1);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $phone,
            'password' => Hash::make($request->password),
            'user_type' => 'applicant',
            'status' => 'active',
            'email_verified_at' => now(), // Auto-verify
        ]);

        $user->assignRole('applicant');

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('applicant.profile.create')
            ->with('success', 'Account created! Please complete your profile.');
    }
}
