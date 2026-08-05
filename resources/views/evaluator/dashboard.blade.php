@extends('layouts.app')
@section('title', 'Evaluator Dashboard')
@section('content')

<section class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-700 rounded-2xl p-6 sm:p-8 text-white mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold">
                    <i class="fas fa-clipboard-check mr-3"></i> Evaluation Dashboard
                </h1>
                <p class="text-purple-200 mt-2">
                    <strong>Department:</strong> {{ Auth::user()->department ?? 'General' }} | 
                    Welcome back, {{ Auth::user()->name }}
                </p>
            </div>
            <div class="flex gap-3">
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center">
                    <p class="text-3xl font-extrabold">
                        @php $myTotal = \App\Models\EvaluationScore::where('evaluator_id', Auth::id())->distinct('application_id')->count(); @endphp
                        {{ $myTotal }}
                    </p>
                    <p class="text-xs text-purple-200">Evaluated</p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl p-4 text-center">
                    <p class="text-3xl font-extrabold">
                        @php $pending = \App\Models\Application::whereIn('status', ['written_exam', 'interview'])->count(); @endphp
                        {{ $pending }}
                    </p>
                    <p class="text-xs text-purple-200">Pending</p>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm">{{ session('success') }}</div>
    @endif

    <!-- Candidates Table -->
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="p-5 border-b bg-gray-50 flex justify-between items-center">
            <div>
                <h3 class="font-bold text-lg">Candidates for Evaluation</h3>
                <p class="text-xs text-gray-500">Written Exam & Interview stage candidates</p>
            </div>
            <a href="{{ route('evaluator.summary') }}" class="text-sm text-[#0a7aa8] font-semibold hover:underline">
                View Summary →
            </a>
        </div>
        
        @php
            $candidates = \App\Models\Application::with(['vacancy', 'applicant', 'evaluationScores'])
                ->whereIn('status', ['written_exam', 'interview'])
                ->latest()->get();
        @endphp

        @if($candidates->isEmpty())
            <div class="p-12 text-center text-gray-400">
                <i class="fas fa-inbox text-5xl mb-3 block"></i>
                <p class="font-semibold">No candidates awaiting evaluation</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Candidate</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Position</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Department</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Stage</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Experience</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">My Score</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Avg Score</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Status</th>
                            <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($candidates as $app)
                            @php
                                $myScores = $app->evaluationScores->where('evaluator_id', Auth::id());
                                $myAvg = $myScores->avg('score') ?? 0;
                                $allAvg = $app->evaluationScores->avg('score') ?? 0;
                                $vacancyDept = $app->vacancy->department ?? 'General';
                                $userDept = Auth::user()->department;
                                $isMyDept = $userDept && (stripos($vacancyDept, $userDept) !== false || stripos($userDept, $vacancyDept) !== false);
                                $allScored = $myScores->count() >= 3;
                            @endphp
                            <tr class="hover:bg-gray-50 {{ $isMyDept ? 'bg-indigo-50/30' : '' }}">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-purple-100 rounded-full flex items-center justify-center text-sm font-bold text-purple-600">
                                            {{ substr($app->applicant->full_name_en ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold">{{ $app->applicant->full_name_en ?? 'N/A' }}</p>
                                            @if($isMyDept)<span class="text-[10px] bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded">Your Dept</span>@endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-gray-600 text-xs">{{ $app->vacancy->title ?? 'N/A' }}</td>
                                <td class="px-5 py-4 text-xs text-gray-500">{{ Str::limit($vacancyDept, 25) }}</td>
                                <td class="px-5 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                        @if($app->status == 'written_exam') bg-purple-100 text-purple-700
                                        @else bg-orange-100 text-orange-700 @endif">
                                        {{ $app->status == 'written_exam' ? 'Written' : 'Interview' }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-center">{{ $app->applicant->total_years_exp ?? 0 }}y</td>
                                <td class="px-5 py-4 text-center font-bold {{ $myAvg > 0 ? 'text-green-600' : 'text-gray-300' }}">
                                    {{ $myAvg > 0 ? number_format($myAvg,0).'%' : '-' }}
                                </td>
                                <td class="px-5 py-4 text-center font-bold {{ $allAvg >= 70 ? 'text-green-600' : ($allAvg >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ $allAvg > 0 ? number_format($allAvg,0).'%' : '-' }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($allScored)
                                        <span class="text-green-600 text-xs font-semibold">✅ Complete</span>
                                    @elseif($myAvg > 0)
                                        <span class="text-yellow-600 text-xs font-semibold">⏳ Partial</span>
                                    @else
                                        <span class="text-red-500 text-xs font-semibold">❌ Not Scored</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-center">
                                    <a href="{{ route('evaluations.scorecard', $app) }}" 
                                        class="px-4 py-2 bg-purple-600 text-white rounded-xl text-xs font-bold hover:bg-purple-700 transition shadow-sm">
                                        @if($myAvg > 0) Update @else Evaluate @endif
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</section>
@endsection
