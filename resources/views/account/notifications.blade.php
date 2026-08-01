@extends('layouts.app')

@section('title', 'Notification Preferences')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h2 class="text-2xl font-semibold mb-6">Notification Preferences</h2>

                <form action="{{ route('account.update-notifications') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    @php
                        $preferences = $user->notification_preferences ? json_decode($user->notification_preferences, true) : [];
                    @endphp

                    <div class="space-y-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-semibold mb-4">Communication Channels</h3>
                            
                            <div class="space-y-3">
                                <label class="flex items-center justify-between">
                                    <div>
                                        <span class="font-medium text-gray-700">Email Notifications</span>
                                        <p class="text-sm text-gray-500">Receive notifications via email</p>
                                    </div>
                                    <input type="checkbox" name="email_notifications" value="1" 
                                        {{ isset($preferences['email_notifications']) && $preferences['email_notifications'] ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                </label>

                                <label class="flex items-center justify-between">
                                    <div>
                                        <span class="font-medium text-gray-700">SMS Notifications</span>
                                        <p class="text-sm text-gray-500">Receive notifications via SMS to {{ $user->phone }}</p>
                                    </div>
                                    <input type="checkbox" name="sms_notifications" value="1"
                                        {{ isset($preferences['sms_notifications']) && $preferences['sms_notifications'] ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                </label>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="font-semibold mb-4">Notification Types</h3>
                            
                            <div class="space-y-3">
                                <label class="flex items-center justify-between">
                                    <div>
                                        <span class="font-medium text-gray-700">Application Updates</span>
                                        <p class="text-sm text-gray-500">Status changes on your job applications</p>
                                    </div>
                                    <input type="checkbox" name="application_updates" value="1"
                                        {{ isset($preferences['application_updates']) && $preferences['application_updates'] ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                </label>

                                <label class="flex items-center justify-between">
                                    <div>
                                        <span class="font-medium text-gray-700">Interview Reminders</span>
                                        <p class="text-sm text-gray-500">Reminders about scheduled interviews</p>
                                    </div>
                                    <input type="checkbox" name="interview_reminders" value="1"
                                        {{ isset($preferences['interview_reminders']) && $preferences['interview_reminders'] ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                </label>

                                <label class="flex items-center justify-between">
                                    <div>
                                        <span class="font-medium text-gray-700">Job Alerts</span>
                                        <p class="text-sm text-gray-500">New vacancy postings matching your profile</p>
                                    </div>
                                    <input type="checkbox" name="job_alerts" value="1"
                                        {{ isset($preferences['job_alerts']) && $preferences['job_alerts'] ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center">
                        <a href="{{ route('account.settings') }}" class="text-blue-600 hover:text-blue-800">← Back to Account Settings</a>
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Save Preferences
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
