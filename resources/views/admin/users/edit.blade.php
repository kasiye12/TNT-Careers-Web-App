@extends('layouts.app')
@section('title', 'Edit User')
@section('content')

<section class="max-w-2xl mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Edit User</h1>
            <p class="text-gray-500 mt-1">{{ $user->name }} - {{ $user->email }}</p>
        </div>
        <span class="px-3 py-1.5 rounded-full text-xs font-bold
            @if($user->user_type == 'admin') bg-red-100 text-red-700
            @elseif($user->user_type == 'hr_manager') bg-blue-100 text-blue-700
            @else bg-gray-100 text-gray-700 @endif">
            {{ ucfirst(str_replace('_', ' ', $user->user_type)) }}
        </span>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                    class="search-input w-full px-4 py-3 rounded-xl text-sm">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required 
                        class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone *</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required 
                        class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">New Password (leave blank to keep)</label>
                    <input type="password" name="password" class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Min. 8 characters">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm New Password</label>
                    <input type="password" name="password_confirmation" class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Repeat password">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">User Role *</label>
                    <select name="user_type" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        <option value="admin" {{ $user->user_type=='admin'?'selected':'' }}>Administrator</option>
                        <option value="hr_manager" {{ $user->user_type=='hr_manager'?'selected':'' }}>HR Manager</option>
                        <option value="evaluator" {{ $user->user_type=='evaluator'?'selected':'' }}>Evaluator</option>
                        <option value="applicant" {{ $user->user_type=='applicant'?'selected':'' }}>Applicant</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Status *</label>
                    <select name="status" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        <option value="active" {{ $user->status=='active'?'selected':'' }}>Active</option>
                        <option value="suspended" {{ $user->status=='suspended'?'selected':'' }}>Suspended</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.users.index') }}" class="border border-gray-300 text-gray-600 px-6 py-3 rounded-xl font-semibold text-sm hover:bg-gray-50">Cancel</a>
                <button type="submit" class="btn-solid-sky px-8 py-3 rounded-xl font-bold text-sm">
                    <i class="fas fa-save mr-2"></i> Update User
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
