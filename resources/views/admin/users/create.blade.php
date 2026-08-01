@extends('layouts.app')
@section('title', 'Add User')
@section('content')

<section class="max-w-2xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Add New User</h1>
        <p class="text-gray-500 mt-1">Create a new system user with specific role</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" required 
                    class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Enter full name">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required 
                        class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="user@example.com">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone *</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" required 
                        class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="+251...">
                    @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password *</label>
                    <input type="password" name="password" required 
                        class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Min. 8 characters">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required 
                        class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Repeat password">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">User Role *</label>
                <select name="user_type" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                    <option value="">Select Role</option>
                    <option value="admin">Administrator</option>
                    <option value="hr_manager">HR Manager</option>
                    <option value="evaluator">Evaluator</option>
                    <option value="applicant">Applicant</option>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.users.index') }}" class="border border-gray-300 text-gray-600 px-6 py-3 rounded-xl font-semibold text-sm hover:bg-gray-50">Cancel</a>
                <button type="submit" class="btn-solid-sky px-8 py-3 rounded-xl font-bold text-sm">
                    <i class="fas fa-user-plus mr-2"></i> Create User
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
