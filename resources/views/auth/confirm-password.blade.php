@extends('layouts.app')

@section('title', 'Confirm Password')

@section('content')
<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        <div class="mb-6 text-center">
            <h2 class="text-2xl font-bold text-gray-900">Confirm Password</h2>
            <p class="text-gray-600 mt-2">
                This is a secure area of the application. Please confirm your password before continuing.
            </p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700" for="password">
                    Password
                </label>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div>
                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Confirm
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
