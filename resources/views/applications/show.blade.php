@extends('layouts.app')
@section('title', 'Application Details')
@section('content')

@php
    $user = Auth::user();
    $userType = $user->user_type;
    $userDepartment = $user->department;
    $isAdmin = $userType === 'admin';
    $isHR = $userType === 'hr_manager';
    $isEvaluator = $userType === 'evaluator';
    $isApplicant = $userType === 'applicant';
    
    $canMoveStages = in_array($userType, ['admin', 'hr_manager']);
    
    $applicant = $application->applicant;
    $vacancy = $application->vacancy;
    $vacancyDept = $vacancy->department ?? 'N/A';
    $applicantDept = $applicant->user->department ?? null;
    
    $isMyDept = $userDepartment && (
        ($applicantDept && stripos($applicantDept, $userDepartment) !== false) ||
        stripos($vacancyDept, $userDepartment) !== false
    );
    
    $documents = $applicant->documents ?? collect();
    $educations = $applicant->educationHistories ?? collect();
    $experiences = $applicant->workExperiences ?? collect();
    $evaluationScores = $application->evaluationScores ?? collect();
@endphp

<section class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    
    <!-- Header Bar -->
    <div class="bg-white rounded-2xl shadow-sm border p-4 sm:p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="text-xs text-gray-400">{{ $vacancy->vacancy_number ?? 'N/A' }}</span>
                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                        @if($application->status == 'rejected') bg-red-100 text-red-700
                        @elseif($application->status == 'selected') bg-green-100 text-green-700
                        @elseif(in_array($application->status, ['shortlisted','interview','written_exam'])) bg-blue-100 text-blue-700
                        @else bg-yellow-100 text-yellow-700 @endif">
                        {{ ucwords(str_replace('_', ' ', $application->status)) }}
                    </span>
                    @if($vacancyDept)
                        <span class="px-2 py-0.5 bg-indigo-50 text-indigo-600 rounded-full text-xs font-semibold">{{ Str::limit($vacancyDept, 30) }}</span>
                    @endif
                </div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-[#0b3b5a]">{{ $vacancy->title ?? 'Position' }}</h1>
                <p class="text-sm text-gray-500 mt-1">Applied: {{ $application->created_at->format('M d, Y') }} | Applicant: {{ $applicant->full_name_en ?? 'N/A' }}</p>
            </div>
            
            <!-- Quick Stats -->
            <div class="flex gap-3 flex-shrink-0">
                <div class="text-center px-4 py-2 bg-sky-50 rounded-xl">
                    <p class="text-xs text-gray-500">Experience</p>
                    <p class="font-bold text-[#0a7aa8]">{{ $applicant->total_years_exp ?? 0 }} yrs</p>
                </div>
                <div class="text-center px-4 py-2 bg-green-50 rounded-xl">
                    <p class="text-xs text-gray-500">Documents</p>
                    <p class="font-bold text-green-600">{{ $documents->count() }}</p>
                </div>
                <div class="text-center px-4 py-2 bg-purple-50 rounded-xl">
                    <p class="text-xs text-gray-500">Education</p>
                    <p class="font-bold text-purple-600">{{ $educations->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- ACTION BUTTONS (Only for Admin/HR) -->
    @if($canMoveStages && in_array($application->status, ['submitted', 'document_verified', 'shortlisted']))
    <div class="bg-white rounded-2xl p-4 shadow-sm border mb-6">
        <div class="flex flex-wrap gap-3">
            @if($application->status == 'submitted')
                <form action="{{ route('hr.applications.update-status', $application) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="status" value="document_verified">
                    <button class="w-full px-4 py-3 bg-green-600 text-white rounded-xl text-sm font-bold hover:bg-green-700 transition shadow-sm">
                        ✅ Verify Documents
                    </button>
                </form>
            @endif
            @if(in_array($application->status, ['submitted', 'document_verified']))
                <form action="{{ route('hr.applications.update-status', $application) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="status" value="shortlisted">
                    <button class="w-full px-4 py-3 bg-yellow-500 text-white rounded-xl text-sm font-bold hover:bg-yellow-600 transition shadow-sm">
                        ⭐ Shortlist
                    </button>
                </form>
            @endif
            <button onclick="showRejectModal()" class="flex-1 px-4 py-3 bg-red-50 text-red-600 rounded-xl text-sm font-bold hover:bg-red-100 transition border border-red-200">
                ❌ Reject
            </button>
            <a href="{{ route('hr.applications.hr-pdf', $application) }}" class="flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl text-sm font-bold hover:bg-gray-200 transition text-center">
                📄 Download PDF
            </a>
        </div>
    </div>
    @endif

    <!-- Pipeline Stage Actions -->
    @if($canMoveStages && in_array($application->status, ['shortlisted', 'written_exam', 'interview', 'medical_check']))
    <div class="bg-white rounded-2xl p-4 shadow-sm border mb-6">
        <div class="flex flex-wrap gap-3">
            @if($application->status == 'shortlisted')
                <form action="{{ route('hr.applications.update-status', $application) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="status" value="written_exam">
                    <button class="w-full px-4 py-3 bg-purple-600 text-white rounded-xl text-sm font-bold hover:bg-purple-700 transition shadow-sm">
                        ✍️ Move to Written Exam
                    </button>
                </form>
            @endif
            @if($application->status == 'written_exam')
                <form action="{{ route('hr.applications.update-status', $application) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="status" value="interview">
                    <button class="w-full px-4 py-3 bg-orange-600 text-white rounded-xl text-sm font-bold hover:bg-orange-700 transition shadow-sm">
                        🎤 Move to Interview
                    </button>
                </form>
            @endif
            @if($application->status == 'interview')
                <form action="{{ route('hr.applications.update-status', $application) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="status" value="selected">
                    <button class="w-full px-4 py-3 bg-green-600 text-white rounded-xl text-sm font-bold hover:bg-green-700 transition shadow-sm">
                        ✅ Select Candidate
                    </button>
                </form>
            @endif
            <button onclick="showRejectModal()" class="flex-1 px-4 py-3 bg-red-50 text-red-600 rounded-xl text-sm font-bold hover:bg-red-100 transition border border-red-200">
                ❌ Reject
            </button>
        </div>
    </div>
    @endif

    <!-- Selected Candidate - Offer Letter -->
    @if($application->status == 'selected' && $canMoveStages)
    <div class="bg-green-50 rounded-2xl p-4 border border-green-200 mb-6">
        <form action="{{ route('hr.applications.update-status', $application) }}" method="POST" class="flex-1">
            @csrf
            <input type="hidden" name="status" value="selected">
            <a href="{{ route('hr.offer-letters.create', $application) }}" class="w-full px-4 py-3 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition shadow-sm text-center block">
                📄 Generate Offer Letter
            </a>
        </form>
    </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- LEFT COLUMN - 2/3 width -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Applicant Profile Card -->
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                <div class="bg-gradient-to-r from-sky-50 to-blue-50 px-6 py-4 border-b">
                    <h2 class="font-bold text-lg text-[#0b3b5a] flex items-center gap-2">
                        <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user text-blue-600 text-sm"></i>
                        </span>
                        Applicant Profile
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-lg font-bold text-blue-700">
                                {{ strtoupper(substr($applicant->full_name_en ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $applicant->full_name_en ?? 'N/A' }}</p>
                                @if($applicant->full_name_am)
                                    <p class="text-xs text-gray-400">{{ $applicant->full_name_am }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-1">
                            <p class="text-gray-600"><i class="fas fa-envelope w-4 mr-1 text-gray-400"></i> {{ $applicant->user->email ?? 'N/A' }}</p>
                            <p class="text-gray-600"><i class="fas fa-phone w-4 mr-1 text-gray-400"></i> {{ $applicant->user->phone ?? 'N/A' }}</p>
                            <p class="text-gray-600"><i class="fas fa-venus-mars w-4 mr-1 text-gray-400"></i> {{ ucfirst($applicant->gender ?? 'N/A') }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-gray-600"><i class="fas fa-map-pin w-4 mr-1 text-gray-400"></i> {{ $applicant->region ?? 'N/A' }}, {{ $applicant->city ?? '' }}</p>
                            <p class="text-gray-600"><i class="fas fa-flag w-4 mr-1 text-gray-400"></i> {{ $applicant->nationality ?? 'N/A' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-gray-600"><i class="fas fa-briefcase w-4 mr-1 text-gray-400"></i> {{ $applicant->total_years_exp ?? 0 }} years experience</p>
                            @if($applicant->professional_title)
                                <p class="text-gray-600"><i class="fas fa-star w-4 mr-1 text-gray-400"></i> {{ $applicant->professional_title }}</p>
                            @endif
                        </div>
                    </div>
                    
                    @if($applicant->skills)
                    <div class="mt-4 pt-4 border-t">
                        <p class="text-xs font-semibold text-gray-500 mb-2">SKILLS</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach(explode(',', $applicant->skills) as $skill)
                                @if(trim($skill))
                                    <span class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">{{ trim($skill) }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    @if($applicant->languages)
                    <div class="mt-3">
                        <p class="text-xs font-semibold text-gray-500 mb-2">LANGUAGES</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach(explode("\n", $applicant->languages) as $lang)
                                @if(trim($lang))
                                    <span class="px-2.5 py-1 bg-green-50 text-green-700 rounded-full text-xs font-medium">{{ trim($lang) }}</span>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Education Section -->
            @if($educations->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                <div class="bg-gradient-to-r from-purple-50 to-pink-50 px-6 py-4 border-b">
                    <h2 class="font-bold text-lg text-[#0b3b5a] flex items-center gap-2">
                        <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-graduation-cap text-purple-600 text-sm"></i>
                        </span>
                        Education ({{ $educations->count() }})
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($educations as $edu)
                            <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-xl">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-university text-purple-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-sm">{{ $edu->institution }}</p>
                                    <p class="text-xs text-gray-500">{{ $edu->qualification_label ?? $edu->qualification }} in {{ $edu->field_of_study }}</p>
                                    <div class="flex gap-3 mt-1 text-xs text-gray-400">
                                        @if($edu->cgpa)<span>CGPA: {{ $edu->cgpa }}</span>@endif
                                        <span>Year: {{ $edu->graduation_year }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Work Experience Section -->
            @if($experiences->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                <div class="bg-gradient-to-r from-orange-50 to-amber-50 px-6 py-4 border-b">
                    <h2 class="font-bold text-lg text-[#0b3b5a] flex items-center gap-2">
                        <span class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-hard-hat text-orange-600 text-sm"></i>
                        </span>
                        Work Experience ({{ $experiences->count() }})
                    </h2>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($experiences as $exp)
                            <div class="border-l-4 border-l-orange-400 pl-4 py-2">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="font-semibold text-sm">{{ $exp->position_held }}</p>
                                        <p class="text-sm text-[#0a7aa8] font-medium">{{ $exp->organization_name }}</p>
                                    </div>
                                    <span class="text-xs text-gray-400 whitespace-nowrap">
                                        {{ $exp->from_date ? $exp->from_date->format('M Y') : '' }} - 
                                        {{ $exp->is_current ? 'Present' : ($exp->to_date ? $exp->to_date->format('M Y') : 'N/A') }}
                                    </span>
                                </div>
                                @if($exp->project_type)
                                    <p class="text-xs text-gray-500 mt-1">Project: {{ $exp->project_type }}</p>
                                @endif
                                @if($exp->key_responsibilities)
                                    <p class="text-xs text-gray-500 mt-1">{{ Str::limit($exp->key_responsibilities, 150) }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Evaluation Scores (for HR/Admin/Evaluator) -->
            @if(!$isApplicant && $evaluationScores->isNotEmpty())
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 px-6 py-4 border-b">
                    <h2 class="font-bold text-lg text-[#0b3b5a] flex items-center gap-2">
                        <span class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-star text-indigo-600 text-sm"></i>
                        </span>
                        Evaluation Scores
                    </h2>
                </div>
                <div class="p-6">
                    @php
                        $academicScore = $evaluationScores->where('evaluation_type','academic_experience')->avg('score') ?? 0;
                        $writtenScore = $evaluationScores->where('evaluation_type','written_exam')->avg('score') ?? 0;
                        $interviewScore = $evaluationScores->where('evaluation_type','panel_interview')->avg('score') ?? 0;
                        $totalScore = ($academicScore * 0.3) + ($writtenScore * 0.4) + ($interviewScore * 0.3);
                    @endphp
                    <div class="grid grid-cols-3 gap-4 mb-4">
                        <div class="text-center p-3 bg-blue-50 rounded-xl">
                            <p class="text-xs text-gray-500">Academic (30%)</p>
                            <p class="text-xl font-extrabold text-blue-600">{{ number_format($academicScore,1) }}%</p>
                        </div>
                        <div class="text-center p-3 bg-green-50 rounded-xl">
                            <p class="text-xs text-gray-500">Written (40%)</p>
                            <p class="text-xl font-extrabold text-green-600">{{ number_format($writtenScore,1) }}%</p>
                        </div>
                        <div class="text-center p-3 bg-purple-50 rounded-xl">
                            <p class="text-xs text-gray-500">Interview (30%)</p>
                            <p class="text-xl font-extrabold text-purple-600">{{ number_format($interviewScore,1) }}%</p>
                        </div>
                    </div>
                    <div class="text-center p-4 rounded-xl {{ $totalScore >= 70 ? 'bg-green-50' : 'bg-red-50' }}">
                        <p class="text-sm">Weighted Total</p>
                        <p class="text-3xl font-extrabold {{ $totalScore >= 70 ? 'text-green-600' : 'text-red-600' }}">{{ number_format($totalScore,1) }}%</p>
                        <p class="text-xs mt-1">{{ $totalScore >= 70 ? '✅ PASSED' : '❌ FAILED' }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- RIGHT SIDEBAR - 1/3 width -->
        <div class="space-y-6">
            
            <!-- Position Details Card -->
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 px-6 py-4 border-b">
                    <h3 class="font-bold text-lg text-[#0b3b5a]">Position Details</h3>
                </div>
                <div class="p-6 space-y-3 text-sm">
                    <div class="flex justify-between"><span class="text-gray-500">Department</span><span class="font-semibold">{{ $vacancyDept }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Location</span><span class="font-semibold">{{ $vacancy->duty_station ?? 'N/A' }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Type</span><span class="font-semibold">{{ ucfirst($vacancy->employment_type ?? 'N/A') }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Positions</span><span class="font-semibold">{{ $vacancy->positions_count ?? 1 }}</span></div>
                    <div class="flex justify-between"><span class="text-gray-500">Experience</span><span class="font-semibold">{{ $vacancy->min_years_experience ?? 0 }}+ yrs</span></div>
                </div>
            </div>

            <!-- Documents Card -->
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                <div class="bg-gradient-to-r from-red-50 to-rose-50 px-6 py-4 border-b">
                    <h3 class="font-bold text-lg text-[#0b3b5a] flex items-center gap-2">
                        <span class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-folder-open text-red-600 text-sm"></i>
                        </span>
                        Documents ({{ $documents->count() }})
                    </h3>
                </div>
                <div class="p-6">
                    @if($documents->isEmpty())
                        <p class="text-gray-400 text-sm text-center py-4">No documents uploaded</p>
                    @else
                        <div class="space-y-2">
                            @foreach($documents as $doc)
                                <a href="{{ route('applicant.documents.download', $doc) }}" 
                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-red-50 transition group">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-file-pdf text-red-500"></i>
                                        <div>
                                            <p class="text-sm font-semibold group-hover:text-[#0a7aa8]">
                                                {{ ucwords(str_replace('_', ' ', $doc->document_type)) }}
                                            </p>
                                            <p class="text-xs text-gray-400">{{ number_format($doc->file_size / 1024, 1) }} KB</p>
                                        </div>
                                    </div>
                                    <i class="fas fa-download text-gray-400 group-hover:text-[#0a7aa8]"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Application Timeline -->
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                <div class="bg-gradient-to-r from-gray-50 to-slate-50 px-6 py-4 border-b">
                    <h3 class="font-bold text-lg text-[#0b3b5a]">Timeline</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach([
                            'submitted' => ['📝','Submitted','blue'],
                            'document_verified' => ['✅','Verified','green'],
                            'shortlisted' => ['⭐','Shortlisted','yellow'],
                            'written_exam' => ['✍️','Written Exam','purple'],
                            'interview' => ['🎤','Interview','orange'],
                            'medical_check' => ['🏥','Medical','red'],
                            'selected' => ['🎉','Selected','green'],
                            'rejected' => ['❌','Rejected','red'],
                        ] as $key => $info)
                            @php $isCurrent = $key === $application->status; @endphp
                            <div class="flex items-center gap-3 {{ $isCurrent ? 'opacity-100 font-bold' : 'opacity-50' }}">
                                <span class="text-lg">{{ $info[0] }}</span>
                                <span class="text-sm {{ $isCurrent ? 'text-[#0a7aa8]' : '' }}">{{ $info[1] }}</span>
                                @if($isCurrent)<span class="ml-auto text-xs bg-{{ $info[2] }}-100 text-{{ $info[2] }}-700 px-2 py-0.5 rounded-full">Current</span>@endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- REJECT MODAL -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-xl mx-4">
        <h3 class="text-lg font-bold mb-4">❌ Reject Application</h3>
        <form action="{{ route('hr.applications.update-status', $application) }}" method="POST">
            @csrf
            <input type="hidden" name="status" value="rejected">
            <textarea name="notes" rows="3" class="search-input w-full px-4 py-3 rounded-xl text-sm mb-4" placeholder="Reason for rejection..."></textarea>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-xl text-sm">Cancel</button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-bold">Confirm Reject</button>
            </div>
        </form>
    </div>
</div>
<script>function showRejectModal(){document.getElementById('rejectModal').classList.remove('hidden');}</script>
@endsection
