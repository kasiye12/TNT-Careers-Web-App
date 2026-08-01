@extends('layouts.app')
@section('title', 'Create Account')
@section('content')

<section class="min-h-[85vh] flex items-center justify-center px-4 py-12 bg-gradient-to-br from-[#f0f6fa] via-[#e8f0f8] to-[#f8fafc]">
    <div class="w-full max-w-lg">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-[#0a7aa8] to-[#0b4b6e] rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-xl shadow-sky-400/30">
                <i class="fas fa-user-plus text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-[#0b3b5a] tracking-tight">Create Your Account</h1>
            <p class="text-gray-500 mt-2 text-sm">Join TNT Construction's talent network</p>
        </div>

        <!-- Card -->
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
            <!-- Errors -->
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm">
                    <div class="flex items-start gap-2 mb-2">
                        <i class="fas fa-exclamation-circle mt-0.5"></i>
                        <span class="font-semibold">Please fix the following errors:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-1 ml-2">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <!-- Full Name -->
                <div>
                    <label class="block text-sm font-semibold text-[#0b3b5a] mb-2">Full Name <span class="text-red-400">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-user text-gray-400 group-focus-within:text-[#0a7aa8] transition-colors"></i>
                        </div>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition-all duration-300 outline-none"
                            placeholder="Abebe Kebede Tadesse">
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-[#0b3b5a] mb-2">Email Address <span class="text-red-400">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400 group-focus-within:text-[#0a7aa8] transition-colors"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition-all duration-300 outline-none"
                            placeholder="you@example.com">
                    </div>
                </div>

                <!-- Phone -->
                <div>
                    <label class="block text-sm font-semibold text-[#0b3b5a] mb-2">Phone Number <span class="text-red-400">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-phone text-gray-400 group-focus-within:text-[#0a7aa8] transition-colors"></i>
                        </div>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition-all duration-300 outline-none"
                            placeholder="+251 9XX XXX XXX or 09XX XXX XXX">
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5 ml-1"><i class="fas fa-info-circle mr-1"></i> Ethiopian phone number format</p>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-semibold text-[#0b3b5a] mb-2">Password <span class="text-red-400">*</span></label>
                    <div class="relative group" x-data="{ show: false }">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400 group-focus-within:text-[#0a7aa8] transition-colors"></i>
                        </div>
                        <input :type="show ? 'text' : 'password'" name="password" required
                            class="w-full pl-12 pr-12 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition-all duration-300 outline-none"
                            placeholder="Min. 8 characters">
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                            <i class="fas text-lg" :class="show ? 'fa-eye-slash' : 'fa-eye'"></i>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-semibold text-[#0b3b5a] mb-2">Confirm Password <span class="text-red-400">*</span></label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400 group-focus-within:text-[#0a7aa8] transition-colors"></i>
                        </div>
                        <input type="password" name="password_confirmation" required
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition-all duration-300 outline-none"
                            placeholder="Repeat your password">
                    </div>
                </div>

                <!-- Terms -->
                <div class="flex items-start gap-2.5 pt-2">
                    <input type="checkbox" required class="mt-0.5 w-4 h-4 rounded border-gray-300 text-[#0a7aa8] focus:ring-[#0a7aa8] focus:ring-offset-0 cursor-pointer">
                    <span class="text-xs text-gray-500 leading-relaxed">
                        I agree to the 
                        <a href="#" class="text-[#0a7aa8] font-semibold hover:underline">Terms of Service</a>, 
                        <a href="#" class="text-[#0a7aa8] font-semibold hover:underline">Privacy Policy</a>, and 
                        <a href="#" class="text-[#0a7aa8] font-semibold hover:underline">Data Processing Agreement</a>
                    </span>
                </div>

                <!-- Submit -->
                <button type="submit" 
                    class="w-full py-3.5 bg-gradient-to-r from-[#0a7aa8] to-[#0b5f85] text-white font-bold rounded-2xl hover:from-[#0b5f85] hover:to-[#0b4b6e] transition-all duration-300 shadow-lg shadow-sky-500/25 hover:shadow-xl hover:shadow-sky-500/35 active:scale-[0.98] text-sm tracking-wide">
                    <i class="fas fa-user-check mr-2"></i> Create Free Account
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                <div class="relative flex justify-center text-sm"><span class="px-4 bg-white text-gray-400">Already have an account?</span></div>
            </div>

            <!-- Login Link -->
            <p class="text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-2 text-sm font-bold text-[#0a7aa8] hover:text-[#0b5f85] transition-colors">
                    <i class="fas fa-sign-in-alt"></i> Sign In to Your Account
                </a>
            </p>

            <!-- Benefits -->
            <div class="mt-5 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-2xl border border-green-100">
                <p class="text-xs font-semibold text-green-700 mb-2 flex items-center gap-1">
                    <i class="fas fa-check-circle"></i> Benefits of Creating an Account
                </p>
                <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                    <span class="flex items-center gap-1"><i class="fas fa-check text-green-500 text-[10px]"></i> Apply for jobs</span>
                    <span class="flex items-center gap-1"><i class="fas fa-check text-green-500 text-[10px]"></i> Track applications</span>
                    <span class="flex items-center gap-1"><i class="fas fa-check text-green-500 text-[10px]"></i> Free CV builder</span>
                    <span class="flex items-center gap-1"><i class="fas fa-check text-green-500 text-[10px]"></i> Job alerts</span>
                </div>
            </div>
        </div>

        <!-- Back Link -->
        <div class="text-center mt-5">
            <a href="/" class="text-sm text-gray-400 hover:text-[#0b3b5a] transition-colors">
                <i class="fas fa-arrow-left mr-1"></i> Back to Home
            </a>
        </div>
    </div>
</section>
@endsection
