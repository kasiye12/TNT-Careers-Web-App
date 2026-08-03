@extends('layouts.app')
@section('title', 'My Applications')
@section('content')
<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">My Applications</h1>
        <a href="{{ route('vacancies.public.index') }}" class="btn-solid-sky text-sm px-5 py-2 rounded-xl">Browse Jobs</a>
    </div>
    @if($applications->isEmpty())
        <div class="text-center py-16 bg-white rounded-2xl shadow-sm border"><p class="text-gray-500">No applications yet. <a href="{{ route('vacancies.public.index') }}" class="text-[#0a7aa8] font-semibold">Browse jobs</a></p></div>
    @else
        <div class="space-y-4">
            @foreach($applications as $app)
                <div class="job-card bg-white rounded-2xl p-6 shadow-sm border flex justify-between items-center">
                    <div>
                        <h3 class="font-bold text-[#0b3b5a]">{{ $app->vacancy->title ?? 'N/A' }}</h3>
                        <p class="text-sm text-gray-500">Applied: {{ $app->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold @if($app->status=='rejected')bg-red-100 text-red-600 @elseif($app->status=='selected')bg-green-100 text-green-600 @else bg-sky-100 text-[#0a5f89] @endif">{{ ucwords(str_replace('_',' ',$app->status)) }}</span>
                        <a href="{{ route('applicant.applications.show', $app) }}" class="text-[#0a7aa8] text-sm font-semibold">View →</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $applications->links() }}</div>
    @endif
</section>
@endsection
