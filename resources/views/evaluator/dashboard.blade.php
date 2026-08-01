@extends('layouts.app')
@section('title', 'Evaluator Dashboard')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-700 rounded-2xl p-8 text-white mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-extrabold">
                    <i class="fas fa-clipboard-check mr-3"></i> Evaluator Dashboard
                </h1>
                <p class="text-purple-200 mt-2">Welcome back, {{ Auth::user()->name }}. Evaluate candidates and track scores.</p>
            </div>
            <div class="hidden md:block">
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center">
                    <p class="text-3xl font-extrabold">
                        @php
                            $myTotal = \App\Models\EvaluationScore::where('evaluator_id', Auth::id())
                                ->distinct('application_id')->count();
                        @endphp
                        {{ $myTotal }}
                    </p>
                    <p class="text-xs text-purple-200">Total Evaluated</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
            <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Pending</p>
                    <p class="text-2xl font-extrabold text-purple-600">
                        @php
                            $pendingCount = \App\Models\Application::whereIn('status', ['written_exam', 'interview'])
                                ->whereDoesntHave('evaluationScores', function($q) {
                                    $q->where('evaluator_id', Auth::id());
                                })->count();
                        @endphp
                        {{ $pendingCount }}
                    </p>
                </div>
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clock text-purple-600"></i>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Evaluated</p>
                    <p class="text-2xl font-extrabold text-green-600">{{ $myTotal }}</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600"></i>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Written Exams</p>
                    <p class="text-2xl font-extrabold text-blue-600">{{ \App\Models\Application::where('status','written_exam')->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-pen text-blue-600"></i>
                </div>
            </div>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Interviews</p>
                    <p class="text-2xl font-extrabold text-orange-600">{{ \App\Models\Application::where('status','interview')->count() }}</p>
                </div>
                <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-comments text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden mb-8">
        <div class="border-b bg-gray-50">
            <nav class="flex gap-0" x-data="{ tab: 'pending' }">
                <button @click="tab = 'pending'" 
                    :class="tab === 'pending' ? 'border-b-2 border-purple-500 text-purple-700 bg-white' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-3.5 text-sm font-bold transition">
                    <i class="fas fa-clock mr-2"></i> Pending Evaluations
                    @if($pendingCount > 0)
                        <span class="ml-2 bg-purple-100 text-purple-700 text-xs px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
                    @endif
                </button>
                <button @click="tab = 'completed'" 
                    :class="tab === 'completed' ? 'border-b-2 border-green-500 text-green-700 bg-white' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-3.5 text-sm font-bold transition">
                    <i class="fas fa-check-circle mr-2"></i> Completed Evaluations
                </button>
                <button @click="tab = 'all'" 
                    :class="tab === 'all' ? 'border-b-2 border-blue-500 text-blue-700 bg-white' : 'text-gray-500 hover:text-gray-700'"
                    class="px-6 py-3.5 text-sm font-bold transition">
                    <i class="fas fa-list mr-2"></i> All Candidates
                </button>
            </nav>
        </div>

        <!-- Pending Tab -->
        <div x-show="tab === 'pending'" x-cloak>
            @php
                $pendingCandidates = \App\Models\Application::with(['vacancy', 'applicant', 'evaluationScores'])
                    ->whereIn('status', ['written_exam', 'interview'])
                    ->latest()
                    ->get();
            @endphp
            @include('evaluator.partials.candidates-table', ['candidates' => $pendingCandidates, 'showEvaluate' => true])
        </div>

        <!-- Completed Tab -->
        <div x-show="tab === 'completed'" x-cloak>
            @php
                $completedCandidates = \App\Models\Application::with(['vacancy', 'applicant', 'evaluationScores'])
                    ->whereHas('evaluationScores', function($q) {
                        $q->where('evaluator_id', Auth::id());
                    })
                    ->latest()
                    ->get();
            @endphp
            @include('evaluator.partials.candidates-table', ['candidates' => $completedCandidates, 'showEvaluate' => false])
        </div>

        <!-- All Tab -->
        <div x-show="tab === 'all'" x-cloak>
            @php
                $allCandidates = \App\Models\Application::with(['vacancy', 'applicant', 'evaluationScores'])
                    ->whereIn('status', ['written_exam', 'interview', 'medical_check', 'selected'])
                    ->latest()
                    ->get();
            @endphp
            @include('evaluator.partials.candidates-table', ['candidates' => $allCandidates, 'showEvaluate' => true])
        </div>
    </div>
</section>

<!-- Alpine.js for tabs -->
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<style>[x-cloak] { display: none !important; }</style>
@endsection
