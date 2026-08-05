@extends('layouts.app')
@section('title', 'User Management')
@section('content')

<section class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0b3b5a]">
                <i class="fas fa-users text-blue-500 mr-2"></i> User Management
            </h1>
            <p class="text-gray-500 mt-1">Manage system users, roles, and department assignments</p>
        </div>
        <div class="flex gap-3">
            <span class="text-sm text-gray-500 bg-white rounded-xl px-4 py-2.5 border">
                <strong>{{ $totalUsers }}</strong> Total | <strong class="text-green-600">{{ $activeUsers }}</strong> Active
            </span>
            <a href="{{ route('admin.users.create') }}" class="btn-solid-sky text-sm px-5 py-2.5 rounded-xl shadow-md">
                <i class="fas fa-user-plus mr-2"></i> Add User
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
            <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Search & Filter -->
    <div class="bg-white rounded-2xl shadow-sm border p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                    placeholder="Name, email, phone, department..." 
                    class="search-input w-full px-4 py-2.5 rounded-xl text-sm">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Role</label>
                <select name="role" class="search-input px-4 py-2.5 rounded-xl text-sm">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
                    <option value="hr_manager" {{ request('role')=='hr_manager'?'selected':'' }}>HR Manager</option>
                    <option value="evaluator" {{ request('role')=='evaluator'?'selected':'' }}>Evaluator</option>
                    <option value="applicant" {{ request('role')=='applicant'?'selected':'' }}>Applicant</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Department</label>
                <select name="department" class="search-input px-4 py-2.5 rounded-xl text-sm">
                    <option value="">All Departments</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->name }}" {{ request('department') == $dept->name ? 'selected' : '' }}>
                            {{ $dept->code }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-500 mb-1">Status</label>
                <select name="status" class="search-input px-4 py-2.5 rounded-xl text-sm">
                    <option value="">All</option>
                    <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                    <option value="suspended" {{ request('status')=='suspended'?'selected':'' }}>Suspended</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="btn-solid-sky text-sm px-4 py-2.5 rounded-xl">
                    <i class="fas fa-search mr-1"></i> Filter
                </button>
                <a href="{{ route('admin.users.index') }}" class="border border-gray-300 text-gray-600 text-sm px-4 py-2.5 rounded-xl hover:bg-gray-50">
                    Clear
                </a>
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">User</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Email</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Phone</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Role</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Department</th>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0
                                        @if($user->user_type == 'admin') bg-red-100 text-red-700
                                        @elseif($user->user_type == 'evaluator') bg-purple-100 text-purple-700
                                        @elseif($user->user_type == 'hr_manager') bg-blue-100 text-blue-700
                                        @else bg-gray-100 text-gray-600 @endif">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-gray-600 text-xs">{{ $user->email }}</td>
                            <td class="px-5 py-4 text-gray-600 text-xs">{{ $user->phone }}</td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                    @if($user->user_type == 'admin') bg-red-50 text-red-700
                                    @elseif($user->user_type == 'evaluator') bg-purple-50 text-purple-700
                                    @elseif($user->user_type == 'hr_manager') bg-blue-50 text-blue-700
                                    @else bg-gray-100 text-gray-600 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $user->user_type)) }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                @if($user->department)
                                    <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-semibold">
                                        {{ Str::limit($user->department, 25) }}
                                    </span>
                                @else
                                    <span class="text-gray-300 text-xs italic">Not assigned</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $user->status == 'active' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-600' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->status == 'active' ? 'bg-green-500 animate-pulse' : 'bg-red-500' }}"></span>
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-center gap-1">
                                    <a href="{{ route('admin.users.edit', $user) }}" 
                                        class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" 
                                        title="Edit User">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" 
                                                class="p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition" 
                                                title="{{ $user->status == 'active' ? 'Suspend User' : 'Activate User' }}">
                                                <i class="fas {{ $user->status == 'active' ? 'fa-ban' : 'fa-check-circle' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Permanently delete this user? This cannot be undone.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" 
                                                class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition" 
                                                title="Delete User">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="p-2 text-gray-300" title="This is you">
                                            <i class="fas fa-user"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                                <i class="fas fa-users text-4xl mb-3 block"></i>
                                <p class="font-semibold">No users found</p>
                                <p class="text-sm">Try adjusting your search filters</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-5 py-4 border-t bg-gray-50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
