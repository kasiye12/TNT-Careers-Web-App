@extends('layouts.app')
@section('title', 'User Management')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0b3b5a]">User Management</h1>
            <p class="text-gray-500 mt-1">Manage system users, roles, and permissions</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn-solid-sky text-sm px-5 py-2.5 rounded-xl shadow-lg">
            <i class="fas fa-user-plus mr-2"></i> Add New User
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Total Users</p>
            <p class="text-2xl font-extrabold text-[#0b3b5a]">{{ $totalUsers }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Active</p>
            <p class="text-2xl font-extrabold text-green-600">{{ $activeUsers }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Admins</p>
            <p class="text-2xl font-extrabold text-purple-600">{{ $adminCount }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Applicants</p>
            <p class="text-2xl font-extrabold text-blue-600">{{ $applicantCount }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm">{{ session('error') }}</div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-2xl shadow-sm border mb-6">
        <form action="{{ route('admin.users.index') }}" method="GET" class="p-4 flex flex-wrap gap-3">
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or phone..." 
                    class="search-input w-full px-4 py-2.5 rounded-xl text-sm">
            </div>
            <select name="role" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role')=='admin'?'selected':'' }}>Admin</option>
                <option value="hr_manager" {{ request('role')=='hr_manager'?'selected':'' }}>HR Manager</option>
                <option value="evaluator" {{ request('role')=='evaluator'?'selected':'' }}>Evaluator</option>
                <option value="applicant" {{ request('role')=='applicant'?'selected':'' }}>Applicant</option>
            </select>
            <select name="status" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm">
                <option value="">All Status</option>
                <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
                <option value="suspended" {{ request('status')=='suspended'?'selected':'' }}>Suspended</option>
            </select>
            <button type="submit" class="btn-solid-sky text-sm px-4 py-2.5 rounded-xl">Filter</button>
            <a href="{{ route('admin.users.index') }}" class="border border-gray-300 text-gray-600 text-sm px-4 py-2.5 rounded-xl hover:bg-gray-50">Clear</a>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase">User</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase">Email</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase">Phone</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase">Role</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase">Status</th>
                        <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase">Joined</th>
                        <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold
                                        @if($user->user_type == 'admin') bg-red-100 text-red-700
                                        @elseif($user->user_type == 'hr_manager') bg-blue-100 text-blue-700
                                        @elseif($user->user_type == 'evaluator') bg-purple-100 text-purple-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="font-semibold text-gray-900">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-gray-600">{{ $user->email }}</td>
                            <td class="px-5 py-4 text-gray-600 text-xs">{{ $user->phone }}</td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                    @if($user->user_type == 'admin') bg-red-50 text-red-700
                                    @elseif($user->user_type == 'hr_manager') bg-blue-50 text-blue-700
                                    @elseif($user->user_type == 'evaluator') bg-purple-50 text-purple-700
                                    @else bg-gray-100 text-gray-700 @endif">
                                    {{ ucfirst(str_replace('_', ' ', $user->user_type)) }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $user->status == 'active' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-600' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $user->status == 'active' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-gray-500 text-xs">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="p-2 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition" 
                                            title="{{ $user->status == 'active' ? 'Suspend' : 'Activate' }}">
                                            <i class="fas {{ $user->status == 'active' ? 'fa-ban' : 'fa-check-circle' }}"></i>
                                        </button>
                                    </form>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Delete this user permanently?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                                <i class="fas fa-users text-4xl mb-3 block"></i>
                                <p>No users found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($users->hasPages())
            <div class="px-5 py-4 border-t bg-gray-50">{{ $users->appends(request()->query())->links() }}</div>
        @endif
    </div>
</section>
@endsection
