@extends('layouts.app')
@section('title', 'Evaluation Summary')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Evaluation Summary Report</h1>
        <p class="text-gray-500 mt-1">Complete overview of all candidate evaluations</p>
    </div>

    @php
        $allEvaluated = \App\Models\Application::with(['vacancy', 'applicant', 'evaluationScores'])
            ->whereHas('evaluationScores')
            ->latest()
            ->get();
            
        $totalCandidates = $allEvaluated->count();
        $averageScore = $allEvaluated->avg(function($app) {
            $academic = $app->evaluationScores->where('evaluation_type','academic_experience')->avg('score') ?? 0;
            $written = $app->evaluationScores->where('evaluation_type','written_exam')->avg('score') ?? 0;
            $interview = $app->evaluationScores->where('evaluation_type','panel_interview')->avg('score') ?? 0;
            return ($academic * 0.3) + ($written * 0.4) + ($interview * 0.3);
        });
        
        $passedCount = $allEvaluated->filter(function($app) {
            $academic = $app->evaluationScores->where('evaluation_type','academic_experience')->avg('score') ?? 0;
            $written = $app->evaluationScores->where('evaluation_type','written_exam')->avg('score') ?? 0;
            $interview = $app->evaluationScores->where('evaluation_type','panel_interview')->avg('score') ?? 0;
            return (($academic * 0.3) + ($written * 0.4) + ($interview * 0.3)) >= 70;
        })->count();
    @endphp

    <!-- Summary Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Total Evaluated</p>
            <p class="text-3xl font-extrabold text-[#0b3b5a]">{{ $totalCandidates }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Average Score</p>
            <p class="text-3xl font-extrabold text-blue-600">{{ number_format($averageScore, 1) }}%</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Passed (≥70%)</p>
            <p class="text-3xl font-extrabold text-green-600">{{ $passedCount }}</p>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Pass Rate</p>
            <p class="text-3xl font-extrabold text-purple-600">{{ $totalCandidates > 0 ? round(($passedCount/$totalCandidates)*100) : 0 }}%</p>
        </div>
    </div>

    <!-- All Evaluated Candidates -->
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="p-5 border-b bg-gray-50">
            <h2 class="font-bold text-lg">All Evaluated Candidates</h2>
        </div>
        
        @if($allEvaluated->isEmpty())
            <div class="p-12 text-center text-gray-400">No evaluations yet.</div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-3">#</th>
                            <th class="text-left px-4 py-3">Candidate</th>
                            <th class="text-left px-4 py-3">Position</th>
                            <th class="text-center px-4 py-3">Academic</th>
                            <th class="text-center px-4 py-3">Written</th>
                            <th class="text-center px-4 py-3">Interview</th>
                            <th class="text-center px-4 py-3">Total</th>
                            <th class="text-center px-4 py-3">Result</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($allEvaluated as $index => $app)
                            @php
                                $academic = $app->evaluationScores->where('evaluation_type','academic_experience')->avg('score') ?? 0;
                                $written = $app->evaluationScores->where('evaluation_type','written_exam')->avg('score') ?? 0;
                                $interview = $app->evaluationScores->where('evaluation_type','panel_interview')->avg('score') ?? 0;
                                $total = ($academic * 0.3) + ($written * 0.4) + ($interview * 0.3);
                            @endphp
                            <tr class="hover:bg-gray-50 {{ $total >= 70 ? 'bg-green-50/20' : '' }}">
                                <td class="px-4 py-4">{{ $index + 1 }}</td>
                                <td class="px-4 py-4 font-semibold">{{ $app->applicant->full_name_en ?? 'N/A' }}</td>
                                <td class="px-4 py-4 text-gray-600">{{ $app->vacancy->title ?? 'N/A' }}</td>
                                <td class="px-4 py-4 text-center font-bold">{{ number_format($academic,1) }}%</td>
                                <td class="px-4 py-4 text-center font-bold">{{ number_format($written,1) }}%</td>
                                <td class="px-4 py-4 text-center font-bold">{{ number_format($interview,1) }}%</td>
                                <td class="px-4 py-4 text-center font-extrabold text-lg {{ $total >= 70 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($total,1) }}%
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $total >= 70 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $total >= 70 ? '✅ PASSED' : '❌ FAILED' }}
                                    </span>
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
