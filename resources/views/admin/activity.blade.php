@extends('layouts.app')
@section('title', 'Activity Log')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">
            <i class="fas fa-history text-blue-500 mr-2"></i> Activity Log
        </h1>
        <p class="text-gray-500 mt-1">Recent system activities and user actions</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">User</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Action</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Description</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Date/Time</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @php
                    $activities = \App\Models\Application::with(['applicant.user', 'vacancy'])->latest()->take(20)->get();
                @endphp
                @foreach($activities as $activity)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4 font-semibold">{{ $activity->applicant->full_name_en ?? 'System' }}</td>
                        <td class="px-5 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                @if($activity->status == 'selected') bg-green-100 text-green-700
                                @elseif($activity->status == 'rejected') bg-red-100 text-red-600
                                @else bg-blue-100 text-blue-700 @endif">
                                {{ ucwords(str_replace('_', ' ', $activity->status)) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-gray-500">Applied for {{ $activity->vacancy->title ?? 'N/A' }}</td>
                        <td class="px-5 py-4 text-xs text-gray-400">{{ $activity->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection
