@extends('layouts.app')
@section('title', 'Application Details')
@section('content')

<section class="max-w-4xl mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <p class="text-sm text-gray-400 mb-1">{{ $application->vacancy->vacancy_number }}</p>
            <h1 class="text-2xl font-extrabold text-[#0b3b5a]">{{ $application->vacancy->title }}</h1>
            <p class="text-gray-500 text-sm mt-1">Application submitted on {{ $application->submitted_at ? $application->submitted_at->format('M d, Y') : $application->created_at->format('M d, Y') }}</p>
        </div>
        <span class="px-4 py-2 rounded-full text-sm font-bold
            @if($application->status == 'rejected') bg-red-100 text-red-700
            @elseif($application->status == 'selected') bg-green-100 text-green-700
            @elseif(in_array($application->status, ['shortlisted','interview','written_exam'])) bg-blue-100 text-blue-700
            @else bg-yellow-100 text-yellow-700
            @endif">
            {{ ucwords(str_replace('_', ' ', $application->status)) }}
        </span>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="md:col-span-2 space-y-6">
            <!-- Applicant Info -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70">
                <h3 class="font-bold text-lg text-[#0b3b5a] mb-4"><i class="fas fa-user mr-2 text-[#0a7aa8]"></i> Applicant Information</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Full Name:</span><p class="font-semibold">{{ $application->applicant->full_name_en }}</p></div>
                    <div><span class="text-gray-500">Email:</span><p class="font-semibold">{{ $application->applicant->user->email }}</p></div>
                    <div><span class="text-gray-500">Phone:</span><p class="font-semibold">{{ $application->applicant->user->phone }}</p></div>
                    <div><span class="text-gray-500">Gender:</span><p class="font-semibold">{{ ucfirst($application->applicant->gender) }}</p></div>
                    <div><span class="text-gray-500">Experience:</span><p class="font-semibold">{{ $application->applicant->total_years_exp }} years</p></div>
                    <div><span class="text-gray-500">Nationality:</span><p class="font-semibold">{{ $application->applicant->nationality }}</p></div>
                </div>
            </div>

            <!-- Position Details -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70">
                <h3 class="font-bold text-lg text-[#0b3b5a] mb-4"><i class="fas fa-briefcase mr-2 text-[#0a7aa8]"></i> Position Details</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><span class="text-gray-500">Position:</span><p class="font-semibold">{{ $application->vacancy->title }}</p></div>
                    <div><span class="text-gray-500">Department:</span><p class="font-semibold">{{ $application->vacancy->department }}</p></div>
                    <div><span class="text-gray-500">Duty Station:</span><p class="font-semibold">{{ $application->vacancy->duty_station }}</p></div>
                    <div><span class="text-gray-500">Type:</span><p class="font-semibold">{{ ucfirst($application->vacancy->employment_type) }}</p></div>
                </div>
            </div>

            <!-- Education -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70">
                <h3 class="font-bold text-lg text-[#0b3b5a] mb-4"><i class="fas fa-graduation-cap mr-2 text-[#0a7aa8]"></i> Education</h3>
                @if($application->applicant->educationHistories->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($application->applicant->educationHistories as $edu)
                            <div class="p-3 bg-gray-50 rounded-xl">
                                <p class="font-semibold text-sm">{{ $edu->institution }}</p>
                                <p class="text-xs text-gray-500">{{ $edu->qualification_label }} in {{ $edu->field_of_study }} | {{ $edu->graduation_year }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400">No education records.</p>
                @endif
            </div>

            <!-- Work Experience -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70">
                <h3 class="font-bold text-lg text-[#0b3b5a] mb-4"><i class="fas fa-hard-hat mr-2 text-[#0a7aa8]"></i> Work Experience</h3>
                @if($application->applicant->workExperiences->isNotEmpty())
                    <div class="space-y-3">
                        @foreach($application->applicant->workExperiences as $exp)
                            <div class="p-3 bg-gray-50 rounded-xl">
                                <p class="font-semibold text-sm">{{ $exp->organization_name }}</p>
                                <p class="text-xs text-gray-500">{{ $exp->position_held }} | {{ $exp->from_date->format('M Y') }} - {{ $exp->is_current ? 'Present' : ($exp->to_date ? $exp->to_date->format('M Y') : 'N/A') }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-400">No work experience recorded (Fresh Graduate).</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Timeline -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70">
                <h3 class="font-bold text-lg text-[#0b3b5a] mb-4">Application Timeline</h3>
                <div class="space-y-4">
                    @php
                        $statuses = [
                            'submitted' => ['icon' => 'fa-paper-plane', 'label' => 'Submitted', 'color' => 'blue'],
                            'document_verified' => ['icon' => 'fa-check-circle', 'label' => 'Documents Verified', 'color' => 'green'],
                            'shortlisted' => ['icon' => 'fa-star', 'label' => 'Shortlisted', 'color' => 'yellow'],
                            'written_exam' => ['icon' => 'fa-pen', 'label' => 'Written Exam', 'color' => 'purple'],
                            'interview' => ['icon' => 'fa-comments', 'label' => 'Interview', 'color' => 'orange'],
                            'medical_check' => ['icon' => 'fa-heartbeat', 'label' => 'Medical Check', 'color' => 'red'],
                            'selected' => ['icon' => 'fa-trophy', 'label' => 'Selected', 'color' => 'green'],
                            'rejected' => ['icon' => 'fa-times-circle', 'label' => 'Rejected', 'color' => 'red'],
                        ];
                        $currentFound = false;
                    @endphp
                    @foreach($statuses as $key => $info)
                        @php
                            $isCompleted = !$currentFound;
                            $isCurrent = $key === $application->status;
                            if ($isCurrent) $currentFound = true;
                        @endphp
                        <div class="flex items-center gap-3 {{ $isCompleted ? 'opacity-100' : 'opacity-40' }}">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center {{ $isCompleted ? 'bg-' . $info['color'] . '-100' : 'bg-gray-200' }}">
                                <i class="fas {{ $info['icon'] }} text-sm {{ $isCompleted ? 'text-' . $info['color'] . '-600' : 'text-gray-400' }}"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium {{ $isCurrent ? 'text-[#0a7aa8] font-bold' : 'text-gray-700' }}">{{ $info['label'] }}</p>
                                @if($isCurrent)
                                    <p class="text-xs text-gray-400">Current Status</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70">
                <h3 class="font-bold text-lg text-[#0b3b5a] mb-4">Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('applicant.applications') }}" class="block text-center border border-gray-300 text-gray-600 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50 transition">
                        ← Back to My Applications
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
