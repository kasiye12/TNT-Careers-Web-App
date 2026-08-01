@extends('layouts.app')
@section('title', 'Sign In')
@section('content')

<section class="min-h-[85vh] flex items-center justify-center px-4 py-12 bg-gradient-to-br from-[#f0f6fa] to-[#e8f0f8]">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-[#0a7aa8] to-[#0b4b6e] rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-xl shadow-sky-400/30">
                <i class="fas fa-hard-hat text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Welcome Back</h1>
            <p class="text-gray-500 text-sm mt-1">Sign in to your account</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
            @if(session('error'))
                <div class="bg-red-50 text-red-600 p-3 rounded-xl mb-4 text-sm">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-50 text-red-600 p-3 rounded-xl mb-4 text-sm">{{ $errors->first() }}</div>
            @endif

            <!-- Google Sign In Button -->
            <a href="{{ route('google.login') }}" 
                class="w-full flex items-center justify-center gap-3 py-3 bg-white border-2 border-gray-200 rounded-2xl text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all mb-6">
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Sign in with Google
            </a>

            <!-- Divider -->
            <div class="relative mb-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                <div class="relative flex justify-center text-sm"><span class="px-4 bg-white text-gray-400">or sign in with email</span></div>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-[#0b3b5a] mb-2">Email or Phone</label>
                    <div class="relative group">
                        <i class="fas fa-envelope absolute left-4 top-3.5 text-gray-400 group-focus-within:text-[#0a7aa8]"></i>
                        <input type="text" name="email" value="{{ old('email') }}" required autofocus
                            class="search-input w-full pl-12 pr-4 py-3.5 rounded-2xl text-sm"
                            placeholder="you@example.com or +2519XXXXXXXX">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-[#0b3b5a] mb-2">Password</label>
                    <div class="relative" x-data="{ show: false }">
                        <i class="fas fa-lock absolute left-4 top-3.5 text-gray-400"></i>
                        <input :type="show ? 'text' : 'password'" name="password" required
                            class="search-input w-full pl-12 pr-12 py-3.5 rounded-2xl text-sm"
                            placeholder="••••••••">
                        <button type="button" @click="show = !show" class="absolute right-4 top-3 text-gray-400 hover:text-gray-600">
                            <i :class="show ? 'fa-eye-slash' : 'fa-eye'" class="fas"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2"><input type="checkbox" name="remember" class="rounded border-gray-300 text-[#0a7aa8]"> Remember me</label>
                    <a href="{{ route('password.request') }}" class="text-[#0a7aa8] font-semibold">Forgot Password?</a>
                </div>
                <button type="submit" class="btn-solid-sky w-full py-3.5 rounded-xl font-bold text-sm">Sign In</button>
            </form>
            <p class="text-center text-sm text-gray-500 mt-6">Don't have an account? <a href="{{ route('register') }}" class="text-[#0a7aa8] font-bold">Register</a></p>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endsection
