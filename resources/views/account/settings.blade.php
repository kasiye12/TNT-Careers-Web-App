@extends('layouts.app')

@section('title', 'Account Settings')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Account Navigation -->
        <div class="mb-8">
            <nav class="flex space-x-4 border-b border-gray-200 pb-4">
                <a href="#profile" class="text-blue-600 border-b-2 border-blue-600 pb-4 px-1 text-sm font-medium">Profile</a>
                <a href="#email" class="text-gray-500 hover:text-gray-700 pb-4 px-1 text-sm font-medium">Email & Phone</a>
                <a href="#password" class="text-gray-500 hover:text-gray-700 pb-4 px-1 text-sm font-medium">Password</a>
                <a href="#notifications" class="text-gray-500 hover:text-gray-700 pb-4 px-1 text-sm font-medium">Notifications</a>
                <a href="#sessions" class="text-gray-500 hover:text-gray-700 pb-4 px-1 text-sm font-medium">Sessions</a>
                <a href="#danger" class="text-gray-500 hover:text-gray-700 pb-4 px-1 text-sm font-medium">Danger Zone</a>
            </nav>
        </div>

        <!-- Profile Information -->
        <div id="profile" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Profile Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="text-sm text-gray-600">Name:</span>
                        <p class="font-medium">{{ $user->name }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">User Type:</span>
                        <p class="font-medium">{{ ucfirst($user->user_type) }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Member Since:</span>
                        <p class="font-medium">{{ $user->created_at->format('F d, Y') }}</p>
                    </div>
                    <div>
                        <span class="text-sm text-gray-600">Account Status:</span>
                        <span class="px-2 py-0.5 text-xs rounded-full {{ $user->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Email Update -->
        <div id="email" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Email Address</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Current email: <strong>{{ $user->email }}</strong>
                    @if(!$user->hasVerifiedEmail())
                        <span class="text-yellow-600 ml-2">(Not verified)</span>
                        <form action="{{ route('verification.send') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-blue-600 hover:text-blue-800 ml-2">Resend verification</button>
                        </form>
                    @else
                        <span class="text-green-600 ml-2">✓ Verified</span>
                    @endif
                </p>

                <form action="{{ route('account.update-email') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700">New Email</label>
                        <input type="email" name="email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" name="current_password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Email</button>
                </form>
            </div>
        </div>

        <!-- Phone Update -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Phone Number</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Current phone: <strong>{{ $user->phone }}</strong>
                </p>

                <form action="{{ route('account.update-phone') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700">New Phone Number</label>
                        <input type="text" name="phone" required placeholder="+2519XXXXXXXX or 09XXXXXXXX"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-xs text-gray-500">Format: +2519XXXXXXXX or 09XXXXXXXX</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" name="current_password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Update Phone</button>
                </form>
            </div>
        </div>

        <!-- Password Update -->
        <div id="password" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Change Password</h3>
                <form action="{{ route('account.update-password') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Current Password</label>
                        <input type="password" name="current_password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">New Password</label>
                        <input type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Confirm New Password</label>
                        <input type="password" name="password_confirmation" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Change Password</button>
                </form>
            </div>
        </div>

        <!-- Notification Preferences -->
        <div id="notifications" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Notification Preferences</h3>
                <form action="{{ route('account.update-notifications') }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    
                    @php
                        $preferences = $user->notification_preferences ? json_decode($user->notification_preferences, true) : [];
                    @endphp

                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="email_notifications" value="1" {{ isset($preferences['email_notifications']) && $preferences['email_notifications'] ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Email Notifications</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="sms_notifications" value="1" {{ isset($preferences['sms_notifications']) && $preferences['sms_notifications'] ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">SMS Notifications</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="application_updates" value="1" {{ isset($preferences['application_updates']) && $preferences['application_updates'] ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Application Status Updates</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="interview_reminders" value="1" {{ isset($preferences['interview_reminders']) && $preferences['interview_reminders'] ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Interview Reminders</span>
                        </label>

                        <label class="flex items-center">
                            <input type="checkbox" name="job_alerts" value="1" {{ isset($preferences['job_alerts']) && $preferences['job_alerts'] ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">New Job Alerts</span>
                        </label>
                    </div>

                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Save Preferences</button>
                </form>
            </div>
        </div>

        <!-- Active Sessions -->
        <div id="sessions" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
            <div class="p-6">
                <h3 class="text-lg font-semibold mb-4">Active Sessions</h3>
                
                <p class="text-sm text-gray-600 mb-4">
                    Manage your active login sessions on different devices.
                </p>

                <form action="{{ route('account.terminate-all-sessions') }}" method="POST" class="mb-4">
                    @csrf
                    @method('DELETE')
                    <div class="mb-3">
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Terminate All Other Sessions
                    </button>
                </form>
            </div>
        </div>

        <!-- Danger Zone -->
        <div id="danger" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8 border-2 border-red-200">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-red-600 mb-4">⚠️ Danger Zone</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Once you delete your account, there is no going back. Please be certain.
                </p>

                <button onclick="document.getElementById('deleteModal').classList.remove('hidden')" 
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Delete Account
                </button>

                <!-- Delete Confirmation Modal -->
                <div id="deleteModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
                    <div class="bg-white rounded-lg p-6 max-w-md w-full">
                        <h3 class="text-lg font-semibold text-red-600 mb-4">Delete Account Permanently</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            This action cannot be undone. All your data including applications, documents, and profile information will be permanently deleted.
                        </p>

                        <form action="{{ route('account.delete') }}" method="POST">
                            @csrf
                            @method('DELETE')
                            
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">Enter your password</label>
                                <input type="password" name="password" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700">
                                    Type <strong>DELETE MY ACCOUNT</strong> to confirm
                                </label>
                                <input type="text" name="confirmation" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                            </div>

                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')" 
                                    class="px-4 py-2 bg-gray-300 rounded-md text-gray-700 hover:bg-gray-400">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                    Delete My Account
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
