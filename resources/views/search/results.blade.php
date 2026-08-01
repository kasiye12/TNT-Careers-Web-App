@extends('layouts.app')
@section('title', 'Search Results')
@section('content')

<section class="max-w-5xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-extrabold text-[#0b3b5a] mb-2">Search Results</h1>
    <p class="text-gray-500 mb-8">Results for "<strong>{{ $query }}</strong>"</p>

    @if($vacancies->isNotEmpty())
        <h3 class="font-bold text-lg mb-4">Vacancies ({{ $vacancies->count() }})</h3>
        <div class="space-y-3 mb-8">
            @foreach($vacancies as $v)
                <a href="{{ route('vacancies.public.show', $v) }}" class="block bg-white rounded-2xl p-5 shadow-sm border hover:shadow-md transition">
                    <p class="font-semibold">{{ $v->title }}</p>
                    <p class="text-sm text-gray-500">{{ $v->department }} · {{ $v->vacancy_number }}</p>
                </a>
            @endforeach
        </div>
    @endif

    @if($applications->isNotEmpty())
        <h3 class="font-bold text-lg mb-4">Applications ({{ $applications->count() }})</h3>
        <div class="space-y-3 mb-8">
            @foreach($applications as $app)
                <a href="{{ route('applicant.applications.show', $app) }}" class="block bg-white rounded-2xl p-5 shadow-sm border hover:shadow-md transition">
                    <p class="font-semibold">{{ $app->applicant->full_name_en }} - {{ $app->vacancy->title }}</p>
                    <p class="text-sm text-gray-500">{{ $app->status }}</p>
                </a>
            @endforeach
        </div>
    @endif

    @if($users->isNotEmpty())
        <h3 class="font-bold text-lg mb-4">Users ({{ $users->count() }})</h3>
        <div class="space-y-3">
            @foreach($users as $u)
                <div class="bg-white rounded-2xl p-5 shadow-sm border">
                    <p class="font-semibold">{{ $u->name }}</p>
                    <p class="text-sm text-gray-500">{{ $u->email }} · {{ $u->user_type }}</p>
                </div>
            @endforeach
        </div>
    @endif

    @if($vacancies->isEmpty() && $applications->isEmpty() && $users->isEmpty())
        <div class="text-center py-16 text-gray-400">
            <i class="fas fa-search text-5xl mb-4 block"></i>
            <p class="text-lg">No results found for "{{ $query }}"</p>
        </div>
    @endif
</section>
@endsection
