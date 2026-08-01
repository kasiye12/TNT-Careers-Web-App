<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'string'],  // Accepts email OR phone
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $loginField = $this->input('email');
        $password = $this->input('password');
        $remember = $this->boolean('remember');

        // Try email first
        if (filter_var($loginField, FILTER_VALIDATE_EMAIL)) {
            // Login with email
            if (Auth::attempt(['email' => $loginField, 'password' => $password], $remember)) {
                RateLimiter::clear($this->throttleKey());
                return;
            }
        } else {
            // Login with phone - format the number
            $phone = $loginField;
            if (str_starts_with($phone, '0')) {
                $phone = '+251' . substr($phone, 1);
            }
            if (!str_starts_with($phone, '+')) {
                $phone = '+251' . $phone;
            }
            
            if (Auth::attempt(['phone' => $phone, 'password' => $password], $remember)) {
                RateLimiter::clear($this->throttleKey());
                return;
            }
            
            // Also try email if phone format but could be email
            if (Auth::attempt(['email' => $loginField, 'password' => $password], $remember)) {
                RateLimiter::clear($this->throttleKey());
                return;
            }
        }

        // If all attempts fail
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
