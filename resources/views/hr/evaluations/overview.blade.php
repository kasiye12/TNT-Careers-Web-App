@extends('layouts.app')
@section('title', 'All Department Evaluations')
@section('content')

<section class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">
            <i class="fas fa-clipboard-list text-purple-600 mr-2"></i> All Department Evaluations
        </h1>
        <p class="text-gray-500 mt-1">View evaluation scores across all departments</p>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-2xl shadow-sm border p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-3">
            <select name="department" class="search-input px-4 py-2.5 rounded-xl text-sm">
                <option value="">All Departments</option>
                @foreach(\App\Models\Department::where('is_active', true)->orderBy('code')->get() as $dept)
                    <option value="{{ $dept->name }}" {{ request('department') == $dept->name ? 'selected' : '' }}>
                        {{ $dept->code }} - {{ $dept->name }}
                    </option>
                @endforeach
            </select>
            <select name="status" class="search-input px-4 py-2.5 rounded-xl text-sm">
                <option value="">All Stages</option>
                <option value="written_exam" {{ request('status') == 'written_exam' ? 'selected' : '' }}>Written Exam</option>
                <option value="interview" {{ request('status') == 'interview' ? 'selected' : '' }}>Interview</option>
                <option value="selected" {{ request('status') == 'selected' ? 'selected' : '' }}>Selected</option>
            </select>
            <button type="submit" class="btn-solid-sky text-sm px-4 py-2.5 rounded-xl">Filter</button>
            <a href="{{ route('hr.evaluations.overview') }}" class="border border-gray-300 text-gray-600 text-sm px-4 py-2.5 rounded-xl">Clear</a>
        </form>
    </div>

    @php
        $query = \App\Models\EvaluationScore::with(['application.vacancy', 'application.applicant', 'evaluator'])
            ->whereHas('application');
        
        if (request('status')) $query->whereHas('application', fn($q) => $q->where('status', request('status')));
        else $query->whereHas('application', fn($q) => $q->whereIn('status', ['written_exam', 'interview', 'selected']));
        
        if (request('department')) $query->where('evaluator_department', request('department'));
        
        $scores = $query->latest()->get()->groupBy('application_id');
    @endphp

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="p-5 border-b bg-gray-50">
            <h3 class="font-bold text-lg">Evaluation Summary ({{ $scores->count() }} candidates)</h3>
        </div>

        @if($scores->isEmpty())
            <div class="p-12 text-center text-gray-400">
                <i class="fas fa-clipboard-list text-5xl mb-3 block"></i>
                <p class="font-semibold">No evaluations found</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Candidate</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Position</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Vacancy Dept</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500">Academic (30%)</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500">Written (40%)</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500">Interview (30%)</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500">Total</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Evaluated By</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($scores as $appId => $appScores)
                            @php
                                $app = $appScores->first()->application;
                                $academic = $appScores->where('evaluation_type','academic_experience')->avg('score') ?? 0;
                                $written = $appScores->where('evaluation_type','written_exam')->avg('score') ?? 0;
                                $interview = $appScores->where('evaluation_type','panel_interview')->avg('score') ?? 0;
                                $total = ($academic * 0.3) + ($written * 0.4) + ($interview * 0.3);
                                $deptScores = $appScores->groupBy('evaluator_department');
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-5 py-4 font-semibold">{{ $app->applicant->full_name_en ?? 'N/A' }}</td>
                                <td class="px-5 py-4 text-gray-600 text-xs">{{ $app->vacancy->title ?? 'N/A' }}</td>
                                <td class="px-5 py-4 text-xs text-gray-500">{{ Str::limit($app->vacancy->department ?? 'N/A', 25) }}</td>
                                <td class="px-4 py-4 text-center font-bold">{{ number_format($academic, 1) }}%</td>
                                <td class="px-4 py-4 text-center font-bold">{{ number_format($written, 1) }}%</td>
                                <td class="px-4 py-4 text-center font-bold">{{ number_format($interview, 1) }}%</td>
                                <td class="px-4 py-4 text-center font-extrabold text-lg {{ $total >= 70 ? 'text-green-600' : 'text-red-600' }}">
                                    {{ number_format($total, 1) }}%
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($deptScores as $dept => $ds)
                                            <span class="px-2 py-0.5 bg-indigo-50 text-indigo-700 rounded-full text-[10px] font-semibold">
                                                {{ Str::limit($dept, 15) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <a href="{{ route('evaluations.scorecard', $app) }}" class="text-[#0a7aa8] text-xs font-semibold hover:underline">View</a>
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
