@extends('layouts.app')
@section('content')

<section class="min-h-[85vh] flex items-center justify-center px-4 bg-gradient-to-br from-[#f0f6fa] to-[#e8f0f8]">
    <div class="w-full max-w-md">
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-[#0a7aa8] to-[#0b4b6e] rounded-2xl flex items-center justify-center mx-auto mb-5 shadow-xl shadow-sky-400/30">
                <i class="fas fa-lock text-white text-2xl"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Reset Password</h1>
            <p class="text-gray-500 text-sm mt-2">Enter your new password below</p>
        </div>

        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8">
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm">
                    <i class="fas fa-exclamation-circle mr-2"></i> {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label class="block text-sm font-semibold text-[#0b3b5a] mb-2">Email Address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email', $request->email) }}" required readonly
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-100 border-2 border-gray-200 rounded-2xl text-sm outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#0b3b5a] mb-2">New Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400 group-focus-within:text-[#0a7aa8]"></i>
                        </div>
                        <input type="password" name="password" required
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition-all outline-none"
                            placeholder="Min. 8 characters">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-[#0b3b5a] mb-2">Confirm Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400 group-focus-within:text-[#0a7aa8]"></i>
                        </div>
                        <input type="password" name="password_confirmation" required
                            class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border-2 border-gray-200 rounded-2xl text-sm focus:bg-white focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition-all outline-none"
                            placeholder="Repeat new password">
                    </div>
                </div>

                <button type="submit" 
                    class="w-full py-3.5 bg-gradient-to-r from-[#0a7aa8] to-[#0b5f85] text-white font-bold rounded-2xl hover:from-[#0b5f85] hover:to-[#0b4b6e] transition-all duration-300 shadow-lg shadow-sky-500/25">
                    <i class="fas fa-check-circle mr-2"></i> Reset Password
                </button>
            </form>
        </div>
    </div>
</section>
@endsection
