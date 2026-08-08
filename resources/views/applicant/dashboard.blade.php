@extends('layouts.app')
@section('title', 'My Dashboard')
@section('content')

@php
    $applicant = Auth::user()->applicant;
    $applications = \App\Models\Application::where('applicant_id', $applicant->id ?? 0)->count();
    $shortlisted = \App\Models\Application::where('applicant_id', $applicant->id ?? 0)->whereIn('status', ['shortlisted','interview'])->count();
    $selected = \App\Models\Application::where('applicant_id', $applicant->id ?? 0)->where('status', 'selected')->count();
@endphp

<section class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    
    @if(!$applicant || !$applicant->profile_completed)
    <!-- PROFILE SETUP WIZARD -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-amber-500 to-orange-600 rounded-2xl p-6 sm:p-8 text-white">
            <h1 class="text-2xl sm:text-3xl font-extrabold">
                <i class="fas fa-user-edit mr-3"></i> Complete Your Profile
            </h1>
            <p class="text-amber-100 mt-2">Follow these steps to start applying for jobs. Experience is optional!</p>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6 mb-8">
        @php
            $step1 = $applicant && $applicant->first_name_en;
            $step2 = $applicant && $applicant->educationHistories->isNotEmpty();
            $step3 = $applicant && $applicant->workExperiences->isNotEmpty();
        @endphp
        
        <!-- Step 1: Personal Info -->
        <a href="{{ $step1 ? route('applicant.profile.edit') : route('applicant.profile.create') }}" 
            class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md transition group {{ $step1 ? 'border-green-300' : 'border-gray-200' }}">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 {{ $step1 ? 'bg-green-100' : 'bg-sky-50' }} rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition">
                    @if($step1)
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    @else
                        <i class="fas fa-user text-[#0a7aa8] text-2xl"></i>
                    @endif
                </div>
                <div>
                    <h3 class="font-bold text-lg">Step 1</h3>
                    <p class="text-sm text-gray-500">Personal Information</p>
                    <span class="text-xs font-semibold {{ $step1 ? 'text-green-600' : 'text-red-500' }}">
                        {{ $step1 ? '✓ Complete' : 'Required' }}
                    </span>
                </div>
            </div>
        </a>

        <!-- Step 2: Education -->
        <a href="{{ route('applicant.education.create') }}" 
            class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md transition group {{ $step2 ? 'border-green-300' : 'border-gray-200' }}">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 {{ $step2 ? 'bg-green-100' : 'bg-sky-50' }} rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition">
                    @if($step2)
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    @else
                        <i class="fas fa-graduation-cap text-[#0a7aa8] text-2xl"></i>
                    @endif
                </div>
                <div>
                    <h3 class="font-bold text-lg">Step 2</h3>
                    <p class="text-sm text-gray-500">Education History</p>
                    <span class="text-xs font-semibold {{ $step2 ? 'text-green-600' : 'text-red-500' }}">
                        {{ $step2 ? '✓ Complete' : 'Required' }}
                    </span>
                </div>
            </div>
        </a>

        <!-- Step 3: Experience (Optional) -->
        <a href="{{ route('applicant.experience.create') }}" 
            class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md transition group {{ $step3 ? 'border-green-300' : 'border-purple-200' }}">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 {{ $step3 ? 'bg-green-100' : 'bg-purple-50' }} rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition">
                    @if($step3)
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    @else
                        <i class="fas fa-briefcase text-purple-600 text-2xl"></i>
                    @endif
                </div>
                <div>
                    <h3 class="font-bold text-lg">Step 3</h3>
                    <p class="text-sm text-gray-500">Work Experience</p>
                    <span class="text-xs font-semibold {{ $step3 ? 'text-green-600' : 'text-purple-600' }}">
                        {{ $step3 ? '✓ Complete' : 'Optional' }}
                    </span>
                </div>
            </div>
        </a>
    </div>

    <!-- Complete Profile Button -->
    @if($step1 && $step2)
    <div class="text-center mb-8">
        <form action="{{ route('applicant.profile.complete') }}" method="POST" class="inline">
            @csrf
            <button type="submit" class="px-10 py-4 bg-green-600 text-white rounded-2xl font-bold text-lg hover:bg-green-700 transition shadow-xl shadow-green-500/25">
                <i class="fas fa-check-circle mr-2"></i> Complete Profile & Start Applying
            </button>
        </form>
        <p class="text-sm text-gray-500 mt-2">Experience is optional. You can add it later.</p>
    </div>
    @endif

    @else
    <!-- COMPLETED PROFILE DASHBOARD -->
    
    <!-- Welcome & Stats -->
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Welcome, {{ Auth::user()->name }}!</h1>
        <p class="text-gray-500 mt-1">Manage your profile and job applications</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Applications</p>
            <p class="text-3xl font-extrabold text-[#0b3b5a]">{{ $applications }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Shortlisted</p>
            <p class="text-3xl font-extrabold text-yellow-600">{{ $shortlisted }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Selected</p>
            <p class="text-3xl font-extrabold text-green-600">{{ $selected }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Experience</p>
            <p class="text-3xl font-extrabold text-purple-600">{{ $applicant->total_years_exp }} yrs</p>
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        <!-- Quick Actions -->
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border">
                <h3 class="font-bold text-lg mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <a href="{{ route('vacancies.public.index') }}" class="p-4 bg-sky-50 rounded-xl text-center hover:bg-sky-100 transition group">
                        <i class="fas fa-search text-[#0a7aa8] text-2xl mb-2 block group-hover:scale-110 transition-transform"></i>
                        <span class="text-sm font-semibold">Browse Jobs</span>
                    </a>
                    <a href="{{ route('applicant.applications') }}" class="p-4 bg-green-50 rounded-xl text-center hover:bg-green-100 transition group">
                        <i class="fas fa-file-alt text-green-600 text-2xl mb-2 block group-hover:scale-110 transition-transform"></i>
                        <span class="text-sm font-semibold">My Applications</span>
                    </a>
                    <a href="{{ route('resume.builder') }}" class="p-4 bg-purple-50 rounded-xl text-center hover:bg-purple-100 transition group">
                        <i class="fas fa-file-alt text-purple-600 text-2xl mb-2 block group-hover:scale-110 transition-transform"></i>
                        <span class="text-sm font-semibold">Resume Builder</span>
                    </a>
                    <a href="{{ route('cv.generator') }}" class="p-4 bg-orange-50 rounded-xl text-center hover:bg-orange-100 transition group">
                        <i class="fas fa-file-pdf text-orange-600 text-2xl mb-2 block group-hover:scale-110 transition-transform"></i>
                        <span class="text-sm font-semibold">CV Generator</span>
                    </a>
                </div>
            </div>

            <!-- Recent Applications -->
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                <div class="p-4 border-b flex justify-between items-center">
                    <h3 class="font-bold">Recent Applications</h3>
                    <a href="{{ route('applicant.applications') }}" class="text-sm text-[#0a7aa8] font-semibold">View All →</a>
                </div>
                @php $recentApps = \App\Models\Application::with('vacancy')->where('applicant_id', $applicant->id)->latest()->take(5)->get(); @endphp
                @if($recentApps->isEmpty())
                    <div class="p-8 text-center text-gray-400">
                        <p>No applications yet.</p>
                        <a href="{{ route('vacancies.public.index') }}" class="text-[#0a7aa8] font-semibold text-sm">Browse Jobs</a>
                    </div>
                @else
                    <div class="divide-y">
                        @foreach($recentApps as $app)
                            <a href="{{ route('applicant.applications.show', $app) }}" class="flex items-center justify-between p-4 hover:bg-gray-50 transition">
                                <div>
                                    <p class="font-semibold text-sm">{{ $app->vacancy->title ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-400">Applied {{ $app->created_at->format('M d, Y') }}</p>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                    @if($app->status == 'rejected') bg-red-100 text-red-700
                                    @elseif($app->status == 'selected') bg-green-100 text-green-700
                                    @elseif(in_array($app->status, ['shortlisted','interview'])) bg-blue-100 text-blue-700
                                    @else bg-yellow-100 text-yellow-700 @endif">
                                    {{ ucwords(str_replace('_', ' ', $app->status)) }}
                                </span>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Profile Summary Sidebar -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border">
                <h3 class="font-bold text-lg mb-4">Profile Summary</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between py-2 border-b"><dt class="text-gray-500">Name</dt><dd class="font-semibold">{{ $applicant->full_name_en }}</dd></div>
                    <div class="flex justify-between py-2 border-b"><dt class="text-gray-500">Email</dt><dd class="font-semibold text-xs">{{ Auth::user()->email }}</dd></div>
                    <div class="flex justify-between py-2 border-b"><dt class="text-gray-500">Phone</dt><dd class="font-semibold">{{ Auth::user()->phone }}</dd></div>
                    <div class="flex justify-between py-2 border-b"><dt class="text-gray-500">Education</dt><dd class="font-semibold">{{ $applicant->educationHistories->count() }} records</dd></div>
                    <div class="flex justify-between py-2 border-b"><dt class="text-gray-500">Experience</dt><dd class="font-semibold">{{ $applicant->workExperiences->count() }} positions</dd></div>
                    <div class="flex justify-between py-2"><dt class="text-gray-500">Documents</dt><dd class="font-semibold">{{ $applicant->documents->count() }} files</dd></div>
                </dl>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('applicant.profile.edit') }}" class="flex-1 text-center border border-gray-300 text-gray-600 py-2 rounded-xl text-xs font-semibold hover:bg-gray-50">Edit Profile</a>
                    <a href="{{ route('applicant.skills.edit') }}" class="flex-1 text-center border border-gray-300 text-gray-600 py-2 rounded-xl text-xs font-semibold hover:bg-gray-50">Skills</a>
                </div>
            </div>

            <!-- Career Tools -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border">
                <h3 class="font-bold text-lg mb-4">Career Tools</h3>
                <div class="space-y-2">
                    <a href="{{ route('resume.builder') }}" class="flex items-center p-2 hover:bg-purple-50 rounded-lg text-sm">
                        <i class="fas fa-file-alt w-5 mr-2 text-purple-500"></i> Resume Builder
                    </a>
                    <a href="{{ route('cv.generator') }}" class="flex items-center p-2 hover:bg-orange-50 rounded-lg text-sm">
                        <i class="fas fa-file-pdf w-5 mr-2 text-orange-500"></i> CV Generator
                    </a>
                    <a href="{{ route('salary.calculator') }}" class="flex items-center p-2 hover:bg-green-50 rounded-lg text-sm">
                        <i class="fas fa-calculator w-5 mr-2 text-green-500"></i> Salary Calculator
                    </a>
                    <a href="{{ route('interview.tips') }}" class="flex items-center p-2 hover:bg-blue-50 rounded-lg text-sm">
                        <i class="fas fa-lightbulb w-5 mr-2 text-blue-500"></i> Interview Tips
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</section>
@endsection
