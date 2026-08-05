@extends('layouts.app')
@section('title', 'Edit User')
@section('content')

<section class="max-w-2xl mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Edit User</h1>
            <p class="text-gray-500 mt-1">{{ $user->name }} - {{ $user->email }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border">
        <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name *</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email *</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone *</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Role *</label>
                    <select name="user_type" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        <option value="admin" {{ $user->user_type=='admin'?'selected':'' }}>Admin</option>
                        <option value="hr_manager" {{ $user->user_type=='hr_manager'?'selected':'' }}>HR Manager</option>
                        <option value="evaluator" {{ $user->user_type=='evaluator'?'selected':'' }}>Evaluator</option>
                        <option value="applicant" {{ $user->user_type=='applicant'?'selected':'' }}>Applicant</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Department</label>
                    <select name="department" class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        <option value="">-- Select Department --</option>
                        @php
                            $departments = \App\Models\Department::where('is_active', true)->orderBy('code')->get();
                        @endphp
                        @if($departments->isEmpty())
                            <option value="" disabled>No departments found</option>
                        @else
                            @foreach($departments as $dept)
                                <option value="{{ $dept->name }}" {{ $user->department == $dept->name ? 'selected' : '' }}>
                                    {{ $dept->code }} - {{ $dept->name }}
                                </option>
                            @endforeach
                        @endif
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

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">New Password (leave blank)</label>
                    <input type="password" name="password" class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('admin.users.index') }}" class="border border-gray-300 text-gray-600 px-6 py-3 rounded-xl font-semibold text-sm">Cancel</a>
                <button type="submit" class="btn-solid-sky px-8 py-3 rounded-xl font-bold text-sm">Update User</button>
            </div>
        </form>
    </div>
</section>
@endsection
