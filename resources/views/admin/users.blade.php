@extends('layouts.app')
@section('title', 'User Management')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0b3b5a]">User Management</h1>
            <p class="text-gray-500 mt-1">Manage system users and their roles</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="p-4 border-b bg-gray-50">
            <div class="flex items-center gap-4">
                <div class="relative flex-1">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    <input type="text" placeholder="Search users..." class="search-input w-full pl-10 pr-4 py-2 rounded-lg text-sm">
                </div>
                <select class="border border-gray-200 rounded-lg px-3 py-2 text-sm">
                    <option value="">All Roles</option>
                    <option>Admin</option>
                    <option>HR Manager</option>
                    <option>Evaluator</option>
                    <option>Applicant</option>
                </select>
            </div>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-6 py-3 font-medium text-gray-500">User</th>
                    <th class="text-left px-6 py-3 font-medium text-gray-500">Email</th>
                    <th class="text-left px-6 py-3 font-medium text-gray-500">Phone</th>
                    <th class="text-left px-6 py-3 font-medium text-gray-500">Role</th>
                    <th class="text-left px-6 py-3 font-medium text-gray-500">Status</th>
                    <th class="text-left px-6 py-3 font-medium text-gray-500">Joined</th>
                </tr>
            </thead>
            <tbody>
                @php $users = \App\Models\User::latest()->paginate(20); @endphp
                @foreach($users as $user)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-sky-100 rounded-full flex items-center justify-center text-sm font-bold text-[#0a7aa8]">{{ substr($user->name, 0, 1) }}</div>
                                <span class="font-semibold">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $user->phone }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                @if($user->user_type == 'admin') bg-red-100 text-red-700
                                @elseif($user->user_type == 'hr_manager') bg-blue-100 text-blue-700
                                @elseif($user->user_type == 'evaluator') bg-purple-100 text-purple-700
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst(str_replace('_', ' ', $user->user_type)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $user->status == 'active' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 border-t">{{ $users->links() }}</div>
    </div>
</section>
@endsection
