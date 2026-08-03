@extends('layouts.app')
@section('content')

<section class="min-h-[85vh] flex items-center justify-center px-4 bg-gradient-to-br from-[#f0f6fa] to-[#e8f0f8]">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-[#0a7aa8] to-[#0b4b6e] rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-xl shadow-sky-400/30">
                <i class="fas fa-key text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Forgot Password?</h1>
            <p class="text-gray-500 text-sm mt-2">Enter your email and we'll send you a reset link</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
            <!-- Session Status -->
            @if(session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
                    <i class="fas fa-check-circle text-green-500"></i> {{ session('status') }}
                </div>
            @endif

            <!-- Errors -->
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm flex items-start gap-2">
                    <i class="fas fa-exclamation-circle mt-0.5"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-[#0b3b5a] mb-2">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400 group-focus-within:text-[#0a7aa8] transition-colors"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition-all outline-none"
                            placeholder="you@example.com">
                    </div>
                </div>

                <button type="submit" 
                    class="w-full py-3.5 bg-gradient-to-r from-[#0a7aa8] to-[#0b5f85] text-white font-bold rounded-2xl hover:from-[#0b5f85] hover:to-[#0b4b6e] transition-all duration-300 shadow-lg shadow-sky-500/25">
                    <i class="fas fa-paper-plane mr-2"></i> Send Reset Link
                </button>
            </form>

            <div class="text-center mt-6">
                <a href="{{ route('login') }}" class="text-sm font-bold text-[#0a7aa8] hover:text-[#0b5f85] transition-colors">
                    <i class="fas fa-arrow-left mr-1"></i> Back to Sign In
                </a>
            </div>
        </div>

        <div class="mt-4 p-4 bg-white/50 rounded-2xl text-center text-xs text-gray-500">
            <i class="fas fa-info-circle mr-1 text-[#0a7aa8]"></i> 
            Check your spam folder if you don't see the email within a few minutes.
        </div>
    </div>
</section>
@endsection
