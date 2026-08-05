@extends('layouts.app')
@section('title', 'Candidate Evaluation')

@push('styles')
<style>
    .score-input { font-size: 24px; font-weight: 800; text-align: center; }
    .score-slider { accent-color: #0a7aa8; }
    .evaluation-card { transition: all 0.2s ease; }
    .evaluation-card:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(0,0,0,0.1); }
    .progress-ring { transition: stroke-dashoffset 0.5s ease; }
</style>
@endpush

@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    
    <!-- Header with Candidate Info -->
    <div class="bg-gradient-to-r from-purple-600 to-indigo-700 rounded-2xl p-6 sm:p-8 text-white mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-purple-200 text-sm mb-1">{{ $application->vacancy->vacancy_number }}</p>
                <h1 class="text-2xl sm:text-3xl font-extrabold">{{ $application->applicant->full_name_en }}</h1>
                <p class="text-purple-200 mt-2">{{ $application->vacancy->title }} · {{ $application->vacancy->department }}</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-center bg-white/20 backdrop-blur-sm rounded-xl p-4">
                    <p class="text-3xl font-extrabold">{{ $application->applicant->total_years_exp ?? 0 }}</p>
                    <p class="text-xs text-purple-200">Years Exp</p>
                </div>
                <div class="text-center bg-white/20 backdrop-blur-sm rounded-xl p-4">
                    <span class="px-3 py-1 rounded-full text-sm font-bold
                        @if($application->status == 'written_exam') bg-purple-500 text-white
                        @else bg-orange-500 text-white @endif">
                        {{ $application->status == 'written_exam' ? 'Written Exam' : 'Interview' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl mb-6 text-sm flex items-center gap-2">
            <i class="fas fa-check-circle text-green-500 text-lg"></i> {{ session('success') }}
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        
        <!-- LEFT: Evaluation Forms -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- 1. Academic & Experience (30%) -->
            <div class="evaluation-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-blue-50 px-6 py-4 border-b border-blue-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-blue-600 text-lg"></i>
                        </span>
                        <div>
                            <h3 class="font-bold text-gray-900">Academic & Experience Verification</h3>
                            <p class="text-xs text-gray-500">Weight: <strong>30%</strong> of total score</p>
                        </div>
                    </div>
                    @php $myAcademic = $application->evaluationScores->where('evaluator_id', Auth::id())->where('evaluation_type','academic_experience')->first(); @endphp
                    @if($myAcademic)
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                            ✅ Scored: {{ $myAcademic->score }}%
                        </span>
                    @else
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">⏳ Pending</span>
                    @endif
                </div>
                <div class="p-6">
                    <form action="{{ route('evaluations.score', $application) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="evaluation_type" value="academic_experience">
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Score <span class="text-gray-400">(0-100)</span>
                            </label>
                            <div class="flex items-center gap-4">
                                <input type="range" name="score_slider" min="0" max="100" value="{{ $myAcademic->score ?? 50 }}" 
                                    class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer score-slider"
                                    oninput="this.nextElementSibling.value = this.value">
                                <output class="text-2xl font-extrabold text-blue-600 w-16 text-center">{{ $myAcademic->score ?? 50 }}</output>
                            </div>
                            <input type="hidden" name="score" value="{{ $myAcademic->score ?? 50 }}" id="academic_score_input">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Comments</label>
                            <textarea name="comments" rows="3" class="search-input w-full px-4 py-3 rounded-xl text-sm resize-y"
                                placeholder="Evaluate education background, certifications, relevant experience...">{{ $myAcademic->comments ?? '' }}</textarea>
                        </div>
                        
                        <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700 transition shadow-md">
                            <i class="fas fa-save mr-2"></i> 
                            {{ $myAcademic ? 'Update Academic Score' : 'Submit Academic Score' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- 2. Written Exam (40%) -->
            <div class="evaluation-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-green-50 px-6 py-4 border-b border-green-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-pen text-green-600 text-lg"></i>
                        </span>
                        <div>
                            <h3 class="font-bold text-gray-900">Written Examination</h3>
                            <p class="text-xs text-gray-500">Weight: <strong>40%</strong> of total score</p>
                        </div>
                    </div>
                    @php $myWritten = $application->evaluationScores->where('evaluator_id', Auth::id())->where('evaluation_type','written_exam')->first(); @endphp
                    @if($myWritten)
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                            ✅ Scored: {{ $myWritten->score }}%
                        </span>
                    @else
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">⏳ Pending</span>
                    @endif
                </div>
                <div class="p-6">
                    <form action="{{ route('evaluations.score', $application) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="evaluation_type" value="written_exam">
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Score <span class="text-gray-400">(0-100)</span>
                            </label>
                            <div class="flex items-center gap-4">
                                <input type="range" min="0" max="100" value="{{ $myWritten->score ?? 50 }}" 
                                    class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                    oninput="this.nextElementSibling.value = this.value; document.getElementById('written_score_input').value = this.value">
                                <output class="text-2xl font-extrabold text-green-600 w-16 text-center">{{ $myWritten->score ?? 50 }}</output>
                            </div>
                            <input type="hidden" name="score" value="{{ $myWritten->score ?? 50 }}" id="written_score_input">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Comments</label>
                            <textarea name="comments" rows="3" class="search-input w-full px-4 py-3 rounded-xl text-sm resize-y"
                                placeholder="Evaluate technical knowledge, problem-solving, accuracy...">{{ $myWritten->comments ?? '' }}</textarea>
                        </div>
                        
                        <button type="submit" class="w-full py-3 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700 transition shadow-md">
                            <i class="fas fa-save mr-2"></i> 
                            {{ $myWritten ? 'Update Written Score' : 'Submit Written Score' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- 3. Panel Interview (30%) -->
            <div class="evaluation-card bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-purple-50 px-6 py-4 border-b border-purple-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-comments text-purple-600 text-lg"></i>
                        </span>
                        <div>
                            <h3 class="font-bold text-gray-900">Panel Interview Performance</h3>
                            <p class="text-xs text-gray-500">Weight: <strong>30%</strong> of total score</p>
                        </div>
                    </div>
                    @php $myInterview = $application->evaluationScores->where('evaluator_id', Auth::id())->where('evaluation_type','panel_interview')->first(); @endphp
                    @if($myInterview)
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                            ✅ Scored: {{ $myInterview->score }}%
                        </span>
                    @else
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">⏳ Pending</span>
                    @endif
                </div>
                <div class="p-6">
                    <form action="{{ route('evaluations.score', $application) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="evaluation_type" value="panel_interview">
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Score <span class="text-gray-400">(0-100)</span>
                            </label>
                            <div class="flex items-center gap-4">
                                <input type="range" min="0" max="100" value="{{ $myInterview->score ?? 50 }}" 
                                    class="flex-1 h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                    oninput="this.nextElementSibling.value = this.value; document.getElementById('interview_score_input').value = this.value">
                                <output class="text-2xl font-extrabold text-purple-600 w-16 text-center">{{ $myInterview->score ?? 50 }}</output>
                            </div>
                            <input type="hidden" name="score" value="{{ $myInterview->score ?? 50 }}" id="interview_score_input">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Comments</label>
                            <textarea name="comments" rows="3" class="search-input w-full px-4 py-3 rounded-xl text-sm resize-y"
                                placeholder="Evaluate communication, confidence, leadership, cultural fit...">{{ $myInterview->comments ?? '' }}</textarea>
                        </div>
                        
                        <button type="submit" class="w-full py-3 bg-purple-600 text-white rounded-xl font-bold text-sm hover:bg-purple-700 transition shadow-md">
                            <i class="fas fa-save mr-2"></i> 
                            {{ $myInterview ? 'Update Interview Score' : 'Submit Interview Score' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- RIGHT: Score Summary -->
        <div class="space-y-6">
            
            <!-- Overall Score Card -->
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-6 sticky top-24">
                <h3 class="font-bold text-lg text-center mb-4">📊 Overall Score</h3>
                
                <div class="grid grid-cols-3 gap-3 mb-6">
                    <div class="text-center p-3 bg-blue-50 rounded-xl">
                        <p class="text-xs text-gray-500">Academic</p>
                        <p class="text-xl font-extrabold text-blue-600">{{ number_format($academicScore, 0) }}%</p>
                        <p class="text-[10px] text-gray-400">30% weight</p>
                    </div>
                    <div class="text-center p-3 bg-green-50 rounded-xl">
                        <p class="text-xs text-gray-500">Written</p>
                        <p class="text-xl font-extrabold text-green-600">{{ number_format($writtenScore, 0) }}%</p>
                        <p class="text-[10px] text-gray-400">40% weight</p>
                    </div>
                    <div class="text-center p-3 bg-purple-50 rounded-xl">
                        <p class="text-xs text-gray-500">Interview</p>
                        <p class="text-xl font-extrabold text-purple-600">{{ number_format($interviewScore, 0) }}%</p>
                        <p class="text-[10px] text-gray-400">30% weight</p>
                    </div>
                </div>
                
                <!-- Weighted Total -->
                <div class="text-center p-6 rounded-xl border-2 mb-4
                    {{ $weightedTotal >= 70 ? 'bg-green-50 border-green-300' : ($weightedTotal >= 50 ? 'bg-yellow-50 border-yellow-300' : 'bg-red-50 border-red-300') }}">
                    <p class="text-sm text-gray-500 mb-1">Weighted Total Score</p>
                    <p class="text-4xl font-extrabold {{ $weightedTotal >= 70 ? 'text-green-600' : ($weightedTotal >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                        {{ number_format($weightedTotal, 1) }}%
                    </p>
                    <p class="text-sm font-bold mt-2 {{ $weightedTotal >= 70 ? 'text-green-600' : 'text-red-600' }}">
                        {{ $weightedTotal >= 70 ? '✅ PASSED' : '❌ FAILED' }} (70% required)
                    </p>
                </div>
                
                <!-- Score Bars -->
                <div class="space-y-3">
                    <div>
                        <div class="flex justify-between text-xs mb-1"><span>Academic (30%)</span><span>{{ number_format($academicScore,0) }}%</span></div>
                        <div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-blue-500 h-2 rounded-full" style="width:{{ $academicScore }}%"></div></div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs mb-1"><span>Written (40%)</span><span>{{ number_format($writtenScore,0) }}%</span></div>
                        <div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-green-500 h-2 rounded-full" style="width:{{ $writtenScore }}%"></div></div>
                    </div>
                    <div>
                        <div class="flex justify-between text-xs mb-1"><span>Interview (30%)</span><span>{{ number_format($interviewScore,0) }}%</span></div>
                        <div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-purple-500 h-2 rounded-full" style="width:{{ $interviewScore }}%"></div></div>
                    </div>
                </div>
            </div>

            <!-- Evaluators Comments -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-lg mb-4">📝 Evaluators' Comments</h3>
                @if($allScores->isEmpty())
                    <p class="text-gray-400 text-center py-4 text-sm">No evaluations submitted yet.</p>
                @else
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        @foreach($allScores->groupBy('evaluator_id') as $evaluatorId => $scores)
                            <div class="border rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-xs font-bold text-indigo-600">
                                        {{ substr($scores->first()->evaluator->name ?? 'E', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-sm">{{ $scores->first()->evaluator->name ?? 'Unknown' }}</p>
                                        <p class="text-xs text-gray-400">{{ $scores->first()->evaluator_department ?? 'General' }}</p>
                                    </div>
                                </div>
                                @foreach($scores as $score)
                                    <div class="ml-10 p-2 bg-gray-50 rounded-lg mb-1 text-sm">
                                        <span class="font-semibold">{{ ucwords(str_replace('_', ' ', $score->evaluation_type)) }}:</span>
                                        <span class="font-bold text-[#0a7aa8]">{{ $score->score }}%</span>
                                        @if($score->comments)
                                            <p class="text-xs text-gray-500 mt-1 italic">"{{ $score->comments }}"</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bottom Actions -->
    <div class="mt-8 flex justify-between items-center">
        <a href="{{ url()->previous() }}" class="text-[#0a7aa8] font-semibold text-sm hover:underline">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
        
        @if(in_array(Auth::user()->user_type, ['admin', 'hr_manager']))
            <div class="flex gap-3">
                @if($application->status == 'written_exam' && $weightedTotal > 0)
                    <form action="{{ route('hr.applications.update-status', $application) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="interview">
                        <button type="submit" class="px-5 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-bold hover:bg-orange-700 shadow-md">
                            Move to Interview →
                        </button>
                    </form>
                @endif
                @if($application->status == 'interview' && $weightedTotal > 0)
                    <form action="{{ route('hr.applications.update-status', $application) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="selected">
                        <button type="submit" class="px-5 py-2.5 bg-green-600 text-white rounded-xl text-sm font-bold hover:bg-green-700 shadow-md">
                            ✅ Select Candidate
                        </button>
                    </form>
                @endif
            </div>
        @endif
    </div>
</section>

<script>
// Update hidden inputs when sliders change
document.querySelectorAll('input[type="range"]').forEach(slider => {
    slider.addEventListener('input', function() {
        const output = this.nextElementSibling;
        output.value = this.value;
        // Also update the hidden input for the specific score type
        const form = this.closest('form');
        const hiddenInput = form.querySelector('input[type="hidden"][name="score"]');
        if (hiddenInput) hiddenInput.value = this.value;
    });
});
</script>
@endsection
