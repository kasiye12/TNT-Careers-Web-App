@extends('layouts.app')
@section('title', 'Candidate Scorecard')
@section('content')

<section class="max-w-5xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Candidate Scorecard</h1>
        <p class="text-gray-500 mt-1">{{ $application->applicant->full_name_en }} - {{ $application->vacancy->title }}</p>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <!-- Academic & Experience (30%) -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-1">Academic & Experience</h3>
            <p class="text-xs text-gray-400 mb-4">Weight: 30%</p>
            <form action="{{ route('evaluations.score', $application) }}" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="evaluation_type" value="academic_experience">
                <input type="number" name="score" min="0" max="100" required class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Score (0-100)">
                <textarea name="comments" rows="2" class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Comments..."></textarea>
                <button type="submit" class="btn-solid-sky w-full py-2.5 text-sm rounded-xl">Submit Score</button>
            </form>
        </div>

        <!-- Written Exam (40%) -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-1">Written Exam</h3>
            <p class="text-xs text-gray-400 mb-4">Weight: 40%</p>
            <form action="{{ route('evaluations.score', $application) }}" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="evaluation_type" value="written_exam">
                <input type="number" name="score" min="0" max="100" required class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Score (0-100)">
                <textarea name="comments" rows="2" class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Comments..."></textarea>
                <button type="submit" class="bg-green-600 text-white w-full py-2.5 text-sm rounded-xl hover:bg-green-700">Submit Score</button>
            </form>
        </div>

        <!-- Interview (30%) -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-1">Panel Interview</h3>
            <p class="text-xs text-gray-400 mb-4">Weight: 30%</p>
            <form action="{{ route('evaluations.score', $application) }}" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="evaluation_type" value="panel_interview">
                <input type="number" name="score" min="0" max="100" required class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Score (0-100)">
                <textarea name="comments" rows="2" class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Comments..."></textarea>
                <button type="submit" class="bg-purple-600 text-white w-full py-2.5 text-sm rounded-xl hover:bg-purple-700">Submit Score</button>
            </form>
        </div>
    </div>

    <!-- Current Scores -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border">
        <h3 class="font-bold text-lg mb-4">Score Summary</h3>
        <div class="grid grid-cols-3 gap-4 text-center">
            <div class="p-4 bg-sky-50 rounded-xl">
                <p class="text-xs text-gray-500">Academic (30%)</p>
                <p class="text-3xl font-extrabold text-[#0a7aa8]">{{ number_format($academicScore, 1) }}%</p>
            </div>
            <div class="p-4 bg-green-50 rounded-xl">
                <p class="text-xs text-gray-500">Written (40%)</p>
                <p class="text-3xl font-extrabold text-green-600">{{ number_format($writtenScore, 1) }}%</p>
            </div>
            <div class="p-4 bg-purple-50 rounded-xl">
                <p class="text-xs text-gray-500">Interview (30%)</p>
                <p class="text-3xl font-extrabold text-purple-600">{{ number_format($interviewScore, 1) }}%</p>
            </div>
        </div>
        <div class="mt-4 p-4 bg-gray-50 rounded-xl text-center">
            <p class="text-sm text-gray-500">Weighted Total</p>
            <p class="text-4xl font-extrabold {{ $weightedTotal >= 70 ? 'text-green-600' : ($weightedTotal >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                {{ number_format($weightedTotal, 1) }}%
            </p>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ url()->previous() }}" class="text-[#0a7aa8] font-semibold text-sm hover:underline">← Back</a>
    </div>
</section>
@endsection
