@extends('layouts.app')
@section('title', 'My Dashboard')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Welcome, {{ Auth::user()->name }}!</h1>
        <p class="text-gray-500 mt-1">Manage your profile and job applications</p>
    </div>

    @php $applicant = Auth::user()->applicant; @endphp

    @if(!$applicant || !$applicant->profile_completed)
        <div class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 mb-8">
            <div class="flex items-center gap-3 mb-3">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                <h3 class="font-bold text-yellow-800 text-lg">Complete Your Profile</h3>
            </div>
            <p class="text-yellow-700">Complete the steps below to start applying. <strong>Experience is optional</strong> for fresh graduates!</p>
        </div>

        <div class="grid md:grid-cols-3 gap-6 mb-8">
            @php
                $step1 = $applicant && $applicant->first_name_en ? true : false;
                $step2 = $applicant && $applicant->educationHistories->isNotEmpty();
                $step3 = $applicant && $applicant->workExperiences->isNotEmpty();
            @endphp
            
            <!-- Step 1: Personal Info -->
            <a href="{{ $step1 ? route('applicant.profile.edit') : route('applicant.profile.create') }}" 
               class="bg-white rounded-2xl p-6 shadow-sm border {{ $step1 ? 'border-green-300 bg-green-50' : 'border-gray-200' }} hover:shadow-md transition text-center">
                <div class="w-14 h-14 {{ $step1 ? 'bg-green-100' : 'bg-sky-50' }} rounded-2xl flex items-center justify-center mx-auto mb-3">
                    @if($step1)<i class="fas fa-check-circle text-green-600 text-2xl"></i>@else<i class="fas fa-user text-[#0a7aa8] text-2xl"></i>@endif
                </div>
                <h4 class="font-bold text-sm">Step 1</h4>
                <p class="text-xs text-gray-500 mt-1">Personal Info</p>
                <p class="text-xs mt-1 font-semibold">Required ✓</p>
            </a>

            <!-- Step 2: Education -->
            <a href="{{ route('applicant.education.create') }}" 
               class="bg-white rounded-2xl p-6 shadow-sm border {{ $step2 ? 'border-green-300 bg-green-50' : 'border-gray-200' }} hover:shadow-md transition text-center">
                <div class="w-14 h-14 {{ $step2 ? 'bg-green-100' : 'bg-sky-50' }} rounded-2xl flex items-center justify-center mx-auto mb-3">
                    @if($step2)<i class="fas fa-check-circle text-green-600 text-2xl"></i>@else<i class="fas fa-graduation-cap text-[#0a7aa8] text-2xl"></i>@endif
                </div>
                <h4 class="font-bold text-sm">Step 2</h4>
                <p class="text-xs text-gray-500 mt-1">Education</p>
                <p class="text-xs mt-1 font-semibold">Required ✓</p>
            </a>

            <!-- Step 3: Experience (Optional) -->
            <a href="{{ route('applicant.experience.create') }}" 
               class="bg-white rounded-2xl p-6 shadow-sm border {{ $step3 ? 'border-green-300 bg-green-50' : 'border-gray-200' }} hover:shadow-md transition text-center">
                <div class="w-14 h-14 {{ $step3 ? 'bg-green-100' : 'bg-purple-50' }} rounded-2xl flex items-center justify-center mx-auto mb-3">
                    @if($step3)<i class="fas fa-check-circle text-green-600 text-2xl"></i>@else<i class="fas fa-briefcase text-purple-600 text-2xl"></i>@endif
                </div>
                <h4 class="font-bold text-sm">Step 3</h4>
                <p class="text-xs text-gray-500 mt-1">Experience</p>
                <p class="text-xs mt-1 font-semibold text-purple-600">Optional</p>
            </a>
        </div>

        <!-- Complete Profile Button -->
        <div class="text-center">
            @if($step1 && $step2)
                <form action="{{ route('applicant.profile.complete') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn-solid-sky text-lg px-10 py-4 rounded-xl font-bold shadow-lg">
                        <i class="fas fa-check-circle mr-2"></i> Complete Profile & Start Applying
                    </button>
                </form>
                <p class="text-sm text-gray-500 mt-2">Experience is optional. You can add it later.</p>
            @else
                <p class="text-gray-500">Complete Steps 1-2 to finalize your profile. Step 3 (Experience) is optional.</p>
            @endif
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border mt-8">
            <h3 class="font-bold mb-3">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <a href="{{ $step1 ? route('applicant.profile.edit') : route('applicant.profile.create') }}" class="p-3 bg-sky-50 rounded-xl text-center hover:bg-sky-100">
                    <i class="fas fa-user-edit text-[#0a7aa8] block mb-1"></i><span class="text-xs font-semibold">Personal Info</span>
                </a>
                <a href="{{ route('applicant.education.create') }}" class="p-3 bg-green-50 rounded-xl text-center hover:bg-green-100">
                    <i class="fas fa-graduation-cap text-green-600 block mb-1"></i><span class="text-xs font-semibold">Education</span>
                </a>
                <a href="{{ route('applicant.experience.create') }}" class="p-3 bg-purple-50 rounded-xl text-center hover:bg-purple-100">
                    <i class="fas fa-briefcase text-purple-600 block mb-1"></i><span class="text-xs font-semibold">Experience (Optional)</span>
                </a>
            </div>
        </div>
    @else
        <!-- Completed Profile -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <div class="stat-card bg-white rounded-2xl p-6 shadow-sm border">
                <p class="text-sm text-gray-500">Applications</p>
                <p class="text-3xl font-extrabold text-[#0b3b5a]">{{ \App\Models\Application::where('applicant_id',$applicant->id)->count() }}</p>
            </div>
            <div class="stat-card bg-white rounded-2xl p-6 shadow-sm border">
                <p class="text-sm text-gray-500">Shortlisted</p>
                <p class="text-3xl font-extrabold text-green-600">{{ \App\Models\Application::where('applicant_id',$applicant->id)->whereIn('status',['shortlisted','interview'])->count() }}</p>
            </div>
            <div class="stat-card bg-white rounded-2xl p-6 shadow-sm border">
                <p class="text-sm text-gray-500">Experience</p>
                <p class="text-3xl font-extrabold text-[#0b3b5a]">{{ $applicant->total_years_exp }} yrs</p>
            </div>
            <div class="stat-card bg-white rounded-2xl p-6 shadow-sm border">
                <p class="text-sm text-gray-500">Education</p>
                <p class="text-3xl font-extrabold text-[#0b3b5a]">{{ $applicant->educationHistories->count() }}</p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-sm border">
                <h3 class="font-bold mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('vacancies.public.index') }}" class="flex items-center p-3 hover:bg-sky-50 rounded-xl">
                        <i class="fas fa-search w-8 text-[#0a7aa8] text-lg"></i>
                        <div><p class="text-sm font-semibold">Browse Jobs</p><p class="text-xs text-gray-500">Find & apply for positions</p></div>
                    </a>
                    <a href="{{ route('applicant.applications') }}" class="flex items-center p-3 hover:bg-sky-50 rounded-xl">
                        <i class="fas fa-file-alt w-8 text-[#0a7aa8] text-lg"></i>
                        <div><p class="text-sm font-semibold">My Applications</p><p class="text-xs text-gray-500">Track your applications</p></div>
                    </a>
                    <a href="{{ route('cv.generator') }}" class="flex items-center p-3 hover:bg-sky-50 rounded-xl">
                        <i class="fas fa-file-pdf w-8 text-[#0a7aa8] text-lg"></i>
                        <div><p class="text-sm font-semibold">Generate CV</p><p class="text-xs text-gray-500">Create professional CV</p></div>
                    </a>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border">
                <h3 class="font-bold mb-4">Profile Summary</h3>
                <dl class="space-y-2 text-sm">
                    <div class="flex justify-between py-2 border-b"><dt class="text-gray-500">Name</dt><dd class="font-semibold">{{ $applicant->full_name_en }}</dd></div>
                    <div class="flex justify-between py-2 border-b"><dt class="text-gray-500">Education</dt><dd class="font-semibold">{{ $applicant->educationHistories->count() }} records</dd></div>
                    <div class="flex justify-between py-2 border-b"><dt class="text-gray-500">Experience</dt><dd class="font-semibold">{{ $applicant->workExperiences->count() }} positions</dd></div>
                    <div class="flex justify-between py-2"><dt class="text-gray-500">Status</dt><dd class="font-semibold text-green-600">✓ Complete</dd></div>
                </dl>
                <div class="mt-4 flex gap-2">
                    <a href="{{ route('applicant.profile.edit') }}" class="text-xs text-[#0a7aa8] hover:underline">Edit Profile</a>
                    <a href="{{ route('applicant.experience.create') }}" class="text-xs text-purple-600 hover:underline">Add Experience</a>
                </div>
            </div>
        </div>
    @endif
</section>
@endsection
