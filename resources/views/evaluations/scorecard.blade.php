@extends('layouts.app')
@section('title', 'Candidate Evaluation')
@section('content')

<section class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    
    <div class="bg-gradient-to-r from-purple-600 to-indigo-700 rounded-2xl p-6 sm:p-8 text-white mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <p class="text-purple-200 text-sm mb-1">{{ $application->vacancy->vacancy_number }}</p>
                <h1 class="text-2xl sm:text-3xl font-extrabold">{{ $application->applicant->full_name_en }}</h1>
                <p class="text-purple-200 mt-2">{{ $application->vacancy->title }} · {{ $application->vacancy->department }}</p>
            </div>
            <div class="flex gap-3">
                <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-3 text-center">
                    <p class="text-2xl font-extrabold">{{ number_format($weightedTotal, 1) }}%</p>
                    <p class="text-xs text-purple-200">Total Score</p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-xl px-4 py-3 text-center">
                    <span class="px-3 py-1 rounded-full text-sm font-bold {{ $weightedTotal >= 70 ? 'bg-green-500' : 'bg-red-500' }} text-white">
                        {{ $weightedTotal >= 70 ? '✅ PASSED' : '❌ FAILED' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm">{{ session('success') }}</div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            
            <!-- 1. Academic & Experience (30%) -->
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                <div class="bg-blue-50 px-6 py-4 border-b flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center"><i class="fas fa-graduation-cap text-blue-600"></i></span>
                        <div><h3 class="font-bold">Academic & Experience Verification</h3><p class="text-xs text-gray-500">Weight: <strong>30%</strong></p></div>
                    </div>
                    @php $myAcademic = $allScores->where('evaluator_id', Auth::id())->where('evaluation_type','academic_experience')->first(); @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $myAcademic ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $myAcademic ? '✅ '.$myAcademic->score.'%' : '⏳ Pending' }}
                    </span>
                </div>
                <div class="p-6">
                    <form action="{{ route('evaluations.score', $application) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="evaluation_type" value="academic_experience">
                        <div class="flex items-center gap-4">
                            <input type="range" min="0" max="100" value="{{ $myAcademic->score ?? 50 }}" class="flex-1 h-2 bg-gray-200 rounded-lg cursor-pointer" oninput="this.nextElementSibling.value=this.value">
                            <output class="text-2xl font-extrabold text-blue-600 w-16 text-center">{{ $myAcademic->score ?? 50 }}</output>
                            <input type="hidden" name="score" value="{{ $myAcademic->score ?? 50 }}">
                        </div>
                        <textarea name="comments" rows="2" class="search-input w-full px-4 py-3 rounded-xl text-sm resize-y" placeholder="Comments...">{{ $myAcademic->comments ?? '' }}</textarea>
                        <button class="w-full py-3 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-700">Submit Academic Score</button>
                    </form>
                </div>
            </div>

            <!-- 2. Written Exam (40%) -->
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                <div class="bg-green-50 px-6 py-4 border-b flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center"><i class="fas fa-pen text-green-600"></i></span>
                        <div><h3 class="font-bold">Written Examination</h3><p class="text-xs text-gray-500">Weight: <strong>40%</strong></p></div>
                    </div>
                    @php $myWritten = $allScores->where('evaluator_id', Auth::id())->where('evaluation_type','written_exam')->first(); @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $myWritten ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $myWritten ? '✅ '.$myWritten->score.'%' : '⏳ Pending' }}
                    </span>
                </div>
                <div class="p-6">
                    <form action="{{ route('evaluations.score', $application) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="evaluation_type" value="written_exam">
                        <div class="flex items-center gap-4">
                            <input type="range" min="0" max="100" value="{{ $myWritten->score ?? 50 }}" class="flex-1 h-2 bg-gray-200 rounded-lg cursor-pointer" oninput="this.nextElementSibling.value=this.value">
                            <output class="text-2xl font-extrabold text-green-600 w-16 text-center">{{ $myWritten->score ?? 50 }}</output>
                            <input type="hidden" name="score" value="{{ $myWritten->score ?? 50 }}">
                        </div>
                        <textarea name="comments" rows="2" class="search-input w-full px-4 py-3 rounded-xl text-sm resize-y" placeholder="Comments...">{{ $myWritten->comments ?? '' }}</textarea>
                        <button class="w-full py-3 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700">Submit Written Score</button>
                    </form>
                </div>
            </div>

            <!-- 3. Panel Interview (30%) -->
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                <div class="bg-purple-50 px-6 py-4 border-b flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center"><i class="fas fa-comments text-purple-600"></i></span>
                        <div><h3 class="font-bold">Panel Interview Performance</h3><p class="text-xs text-gray-500">Weight: <strong>30%</strong></p></div>
                    </div>
                    @php $myInterview = $allScores->where('evaluator_id', Auth::id())->where('evaluation_type','panel_interview')->first(); @endphp
                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $myInterview ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        {{ $myInterview ? '✅ '.$myInterview->score.'%' : '⏳ Pending' }}
                    </span>
                </div>
                <div class="p-6">
                    <form action="{{ route('evaluations.score', $application) }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="evaluation_type" value="panel_interview">
                        <div class="flex items-center gap-4">
                            <input type="range" min="0" max="100" value="{{ $myInterview->score ?? 50 }}" class="flex-1 h-2 bg-gray-200 rounded-lg cursor-pointer" oninput="this.nextElementSibling.value=this.value">
                            <output class="text-2xl font-extrabold text-purple-600 w-16 text-center">{{ $myInterview->score ?? 50 }}</output>
                            <input type="hidden" name="score" value="{{ $myInterview->score ?? 50 }}">
                        </div>
                        <textarea name="comments" rows="2" class="search-input w-full px-4 py-3 rounded-xl text-sm resize-y" placeholder="Comments...">{{ $myInterview->comments ?? '' }}</textarea>
                        <button class="w-full py-3 bg-purple-600 text-white rounded-xl font-bold text-sm hover:bg-purple-700">Submit Interview Score</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-md border p-6 sticky top-24">
                <h3 class="font-bold text-lg text-center mb-4">📊 Score Summary</h3>
                <div class="grid grid-cols-3 gap-3 mb-6">
                    <div class="text-center p-3 bg-blue-50 rounded-xl"><p class="text-xs text-gray-500">Academic</p><p class="text-xl font-extrabold text-blue-600">{{ number_format($academicScore,0) }}%</p><p class="text-[10px] text-gray-400">30%</p></div>
                    <div class="text-center p-3 bg-green-50 rounded-xl"><p class="text-xs text-gray-500">Written</p><p class="text-xl font-extrabold text-green-600">{{ number_format($writtenScore,0) }}%</p><p class="text-[10px] text-gray-400">40%</p></div>
                    <div class="text-center p-3 bg-purple-50 rounded-xl"><p class="text-xs text-gray-500">Interview</p><p class="text-xl font-extrabold text-purple-600">{{ number_format($interviewScore,0) }}%</p><p class="text-[10px] text-gray-400">30%</p></div>
                </div>
                <div class="text-center p-6 rounded-xl border-2 {{ $weightedTotal >= 70 ? 'bg-green-50 border-green-300' : 'bg-red-50 border-red-300' }}">
                    <p class="text-sm text-gray-500 mb-1">Weighted Total Score</p>
                    <p class="text-4xl font-extrabold {{ $weightedTotal >= 70 ? 'text-green-600' : 'text-red-600' }}">{{ number_format($weightedTotal, 1) }}%</p>
                    <p class="text-sm font-bold mt-2 {{ $weightedTotal >= 70 ? 'text-green-600' : 'text-red-600' }}">{{ $weightedTotal >= 70 ? '✅ PASSED' : '❌ FAILED' }}</p>
                </div>
            </div>

            <!-- Evaluators Comments -->
            @if($allScores->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm border p-6">
                <h3 class="font-bold text-lg mb-4">📝 Evaluators' Comments</h3>
                <div class="space-y-3 max-h-80 overflow-y-auto">
                    @foreach($allScores->groupBy('evaluator_id') as $evaluatorId => $scores)
                        <div class="border rounded-xl p-3">
                            <p class="font-semibold text-sm">{{ $scores->first()->evaluator->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-gray-400">{{ $scores->first()->evaluator_department ?? 'General' }}</p>
                            @foreach($scores as $score)
                                <div class="mt-1 text-xs">
                                    <span class="font-semibold">{{ ucwords(str_replace('_',' ',$score->evaluation_type)) }}:</span>
                                    <span class="font-bold text-[#0a7aa8]">{{ $score->score }}%</span>
                                    @if($score->comments)<p class="text-gray-500 italic">"{{ $score->comments }}"</p>@endif
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
@endsection
