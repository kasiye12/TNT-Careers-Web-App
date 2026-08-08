@extends('layouts.app')
@section('title', 'My Applications')
@section('content')

<section class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0b3b5a]">My Applications</h1>
            <p class="text-gray-500 mt-1">Track all your job applications</p>
        </div>
        <a href="{{ route('vacancies.public.index') }}" class="btn-solid-sky text-sm px-5 py-2.5 rounded-xl">
            <i class="fas fa-search mr-2"></i> Browse More Jobs
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm">{{ session('success') }}</div>
    @endif

    @if($applications->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center shadow-sm border">
            <div class="text-6xl mb-4">📋</div>
            <h3 class="text-xl font-bold text-[#0b3b5a] mb-2">No Applications Yet</h3>
            <p class="text-gray-500 mb-4">Start applying for jobs to see your applications here.</p>
            <a href="{{ route('vacancies.public.index') }}" class="btn-solid-sky inline-block px-6 py-3 rounded-xl font-bold text-sm">
                Browse Open Positions
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($applications as $app)
                <div class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md transition">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-sky-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-briefcase text-[#0a7aa8] text-lg"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="font-bold text-lg text-[#0b3b5a]">{{ $app->vacancy->title ?? 'N/A' }}</h3>
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                        @if($app->status == 'rejected') bg-red-100 text-red-700
                                        @elseif($app->status == 'selected') bg-green-100 text-green-700
                                        @elseif(in_array($app->status, ['shortlisted','interview','written_exam'])) bg-blue-100 text-blue-700
                                        @else bg-yellow-100 text-yellow-700 @endif">
                                        {{ ucwords(str_replace('_', ' ', $app->status)) }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-400 mb-2">{{ $app->vacancy->vacancy_number ?? '' }} | {{ $app->vacancy->department ?? '' }}</p>
                                <div class="flex flex-wrap gap-3 text-sm text-gray-500">
                                    <span><i class="fas fa-map-pin mr-1"></i> {{ $app->vacancy->duty_station ?? 'N/A' }}</span>
                                    <span><i class="far fa-calendar mr-1"></i> Applied: {{ $app->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('applicant.applications.show', $app) }}" 
                            class="px-4 py-2 border border-gray-300 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 transition text-center">
                            View Details →
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $applications->links() }}</div>
    @endif
</section>
@endsection
