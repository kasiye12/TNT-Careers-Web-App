@if($candidates->isEmpty())
    <div class="p-12 text-center text-gray-400">
        <i class="fas fa-inbox text-5xl mb-3 block"></i>
        <p class="font-semibold">No candidates found</p>
    </div>
@else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">#</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Candidate</th>
                    <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Position</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500">Stage</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500">Academic (30%)</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500">Written (40%)</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500">Interview (30%)</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500">Total Score</th>
                    <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($candidates as $index => $app)
                    @php
                        // Get scores from ALL evaluators
                        $allAcademic = $app->evaluationScores->where('evaluation_type','academic_experience')->avg('score') ?? 0;
                        $allWritten = $app->evaluationScores->where('evaluation_type','written_exam')->avg('score') ?? 0;
                        $allInterview = $app->evaluationScores->where('evaluation_type','panel_interview')->avg('score') ?? 0;
                        $weightedTotal = ($allAcademic * 0.3) + ($allWritten * 0.4) + ($allInterview * 0.3);
                        
                        // Check if current evaluator has scored
                        $scoredByMe = \App\Models\EvaluationScore::where('application_id', $app->id)
                            ->where('evaluator_id', Auth::id())->exists();
                    @endphp
                    <tr class="hover:bg-gray-50 {{ $scoredByMe ? 'bg-green-50/30' : '' }}">
                        <td class="px-4 py-4 font-medium text-gray-400">{{ $index + 1 }}</td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 bg-purple-100 rounded-lg flex items-center justify-center text-sm font-bold text-purple-600">
                                    {{ substr($app->applicant->full_name_en ?? 'N/A' ?? '?', 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $app->applicant->full_name_en ?? 'N/A' ?? 'N/A' }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $app->applicant->user->email ?? '' ?? '' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            <p class="font-medium text-gray-900">{{ $app->vacancy->title ?? 'N/A' ?? 'N/A' }}</p>
                            <p class="text-[11px] text-gray-400">{{ $app->vacancy->vacancy_number ?? '' }}</p>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <span class="px-2.5 py-1 rounded-full text-[11px] font-semibold
                                @if($app->status == 'written_exam') bg-blue-100 text-blue-700
                                @elseif($app->status == 'interview') bg-orange-100 text-orange-700
                                @elseif($app->status == 'medical_check') bg-red-100 text-red-700
                                @elseif($app->status == 'selected') bg-green-100 text-green-700
                                @else bg-gray-100 text-gray-600 @endif">
                                {{ ucwords(str_replace('_', ' ', $app->status)) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($allAcademic > 0)
                                <span class="font-bold text-sm {{ $allAcademic >= 70 ? 'text-green-600' : ($allAcademic >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($allAcademic, 0) }}%
                                </span>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1 max-w-[60px] mx-auto">
                                    <div class="bg-purple-500 h-1.5 rounded-full" style="width:{{ $allAcademic }}%"></div>
                                </div>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($allWritten > 0)
                                <span class="font-bold text-sm {{ $allWritten >= 70 ? 'text-green-600' : ($allWritten >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($allWritten, 0) }}%
                                </span>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1 max-w-[60px] mx-auto">
                                    <div class="bg-blue-500 h-1.5 rounded-full" style="width:{{ $allWritten }}%"></div>
                                </div>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($allInterview > 0)
                                <span class="font-bold text-sm {{ $allInterview >= 70 ? 'text-green-600' : ($allInterview >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($allInterview, 0) }}%
                                </span>
                                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1 max-w-[60px] mx-auto">
                                    <div class="bg-orange-500 h-1.5 rounded-full" style="width:{{ $allInterview }}%"></div>
                                </div>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($weightedTotal > 0)
                                <span class="font-extrabold text-base {{ $weightedTotal >= 70 ? 'text-green-600' : ($weightedTotal >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($weightedTotal, 0) }}%
                                </span>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-4 text-center">
                            @if($showEvaluate)
                                <a href="{{ route('evaluations.scorecard', $app) }}" 
                                    class="px-4 py-2 bg-purple-600 text-white rounded-xl text-xs font-bold hover:bg-purple-700 transition shadow-sm">
                                    @if($scoredByMe)
                                        <i class="fas fa-edit mr-1"></i> Update
                                    @else
                                        <i class="fas fa-star mr-1"></i> Evaluate
                                    @endif
                                </a>
                            @else
                                <a href="{{ route('evaluations.scorecard', $app) }}" 
                                    class="px-3 py-1.5 border border-gray-300 text-gray-600 rounded-lg text-xs font-semibold hover:bg-gray-50">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
