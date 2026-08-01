@extends('layouts.app')

@section('title', 'Verify Email')

@section('content')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-gray-900">Verify Your Email</h2>
            <p class="text-gray-600 mt-2">
                Thanks for signing up! Please verify your email address to continue.
            </p>
        </div>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600 bg-green-50 p-3 rounded">
                {{ __('A new verification link has been sent to your email address.') }}
            </div>
        @endif

        <div class="mt-4 flex flex-col space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
