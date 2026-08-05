@extends('layouts.app')
@section('title', 'Add User')
@section('content')

<section class="max-w-2xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">
            <i class="fas fa-user-plus text-blue-500 mr-2"></i> Add New User
        </h1>
        <p class="text-gray-500 mt-1">Create a new system user with role and department</p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-6 text-sm">
            <p class="font-semibold mb-2">Please fix the following errors:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl p-6 shadow-sm border">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Full Name <span class="text-red-400">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none"
                    placeholder="Enter full name">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Email <span class="text-red-400">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none"
                        placeholder="user@example.com">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Phone <span class="text-red-400">*</span>
                    </label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none"
                        placeholder="+2519XXXXXXXX">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Password <span class="text-red-400">*</span>
                    </label>
                    <input type="password" name="password" required 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none"
                        placeholder="Minimum 8 characters">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Confirm Password <span class="text-red-400">*</span>
                    </label>
                    <input type="password" name="password_confirmation" required 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none"
                        placeholder="Repeat password">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Role <span class="text-red-400">*</span>
                    </label>
                    <select name="user_type" required 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none bg-white">
                        <option value="">-- Select Role --</option>
                        <option value="admin" {{ old('user_type')=='admin'?'selected':'' }}>👑 Administrator</option>
                        <option value="hr_manager" {{ old('user_type')=='hr_manager'?'selected':'' }}>📊 HR Manager</option>
                        <option value="evaluator" {{ old('user_type')=='evaluator'?'selected':'' }}>🎯 Evaluator</option>
                        <option value="applicant" {{ old('user_type')=='applicant'?'selected':'' }}>👤 Applicant</option>
                    </select>
                    @error('user_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Department <span class="text-gray-400 font-normal">(for evaluators)</span>
                    </label>
                    <select name="department" 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none bg-white">
                        <option value="">-- Select Department --</option>
                        @if(isset($departments) && $departments->isNotEmpty())
                            @foreach($departments as $dept)
                                <option value="{{ $dept->name }}" {{ old('department') == $dept->name ? 'selected' : '' }}>
                                    {{ $dept->code }} - {{ $dept->name }}
                                </option>
                            @endforeach
                        @else
                            <option value="" disabled>No departments available</option>
                        @endif
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.users.index') }}" 
                    class="px-6 py-3 border-2 border-gray-300 text-gray-600 rounded-xl font-semibold text-sm hover:bg-gray-50 transition">
                    <i class="fas fa-times mr-2"></i> Cancel
                </a>
                <button type="submit" 
                    class="px-8 py-3 bg-[#0a7aa8] text-white rounded-xl font-bold text-sm hover:bg-[#0b5f85] transition shadow-lg shadow-sky-500/25">
                    <i class="fas fa-user-plus mr-2"></i> Create User
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
