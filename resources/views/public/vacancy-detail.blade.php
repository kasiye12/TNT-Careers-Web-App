@extends('layouts.app')
@section('content')

<div class="bg-[#0b3b5a] text-white py-12">
    <div class="max-w-4xl mx-auto px-4">
        <p class="text-[#65b7e0] text-sm mb-2">{{ $vacancy->vacancy_number }}</p>
        <h1 class="text-3xl font-bold">{{ $vacancy->title }}</h1>
        <div class="flex flex-wrap gap-4 mt-4 text-gray-300">
            <span><i class="fas fa-building mr-1"></i> {{ $vacancy->department }}</span>
            <span><i class="fas fa-map-pin mr-1"></i> {{ $vacancy->duty_station }}</span>
            <span><i class="fas fa-clock mr-1"></i> {{ ucfirst($vacancy->employment_type) }}</span>
            <span><i class="far fa-calendar mr-1"></i> Closes {{ $vacancy->closing_date->format('M d, Y') }}</span>
        </div>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 py-8">
    <div class="grid md:grid-cols-3 gap-8">
        <div class="md:col-span-2 space-y-6">
            @if($vacancy->description_en)
                <div class="bg-white rounded-2xl p-6 shadow-sm border">
                    <h2 class="font-bold text-lg mb-3">Job Description</h2>
                    <p class="text-gray-600">{{ $vacancy->description_en }}</p>
                </div>
            @endif
            @if($vacancy->requirements_en)
                <div class="bg-white rounded-2xl p-6 shadow-sm border">
                    <h2 class="font-bold text-lg mb-3">Requirements</h2>
                    <p class="text-gray-600">{{ $vacancy->requirements_en }}</p>
                </div>
            @endif
        </div>
        <div>
            <div class="bg-white rounded-2xl p-6 shadow-sm border sticky top-24">
                <h3 class="font-bold text-lg mb-4">Job Summary</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">Experience</dt><dd class="font-semibold">{{ $vacancy->min_years_experience }}+ yrs</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Education</dt><dd class="font-semibold">{{ strtoupper(str_replace('_',' ',$vacancy->min_education_level)) }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Positions</dt><dd class="font-semibold">{{ $vacancy->positions_count }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Deadline</dt><dd class="font-semibold text-red-500">{{ $vacancy->closing_date->format('M d, Y') }}</dd></div>
                </dl>
                
                <!-- PERMISSION-BASED BUTTONS -->
                @auth
                    @if(Auth::user()->user_type === 'applicant')
                        <!-- APPLICANT: Show Apply Button -->
                        <a href="{{ route('applicant.apply', $vacancy) }}" class="btn-solid-sky w-full mt-6 text-center justify-center py-3 rounded-xl font-bold inline-block">
                            <i class="fas fa-paper-plane mr-2"></i> Apply Now
                        </a>
                    @elseif(in_array(Auth::user()->user_type, ['admin', 'hr_manager']))
                        <!-- ADMIN/HR: Show Manage Button -->
                        <div class="mt-6 p-3 bg-yellow-50 rounded-xl text-center text-sm text-yellow-700">
                            <i class="fas fa-info-circle mr-1"></i> You are logged in as {{ ucfirst(Auth::user()->user_type) }}.<br>
                            <a href="{{ route('hr.vacancies.edit', $vacancy) }}" class="text-[#0a7aa8] font-semibold hover:underline">Manage this vacancy →</a>
                        </div>
                    @elseif(Auth::user()->user_type === 'evaluator')
                        <!-- EVALUATOR: Show info -->
                        <div class="mt-6 p-3 bg-purple-50 rounded-xl text-center text-sm text-purple-700">
                            <i class="fas fa-info-circle mr-1"></i> Evaluator account - apply as applicant to submit applications.
                        </div>
                    @endif
                @else
                    <!-- GUEST: Show Sign In to Apply -->
                    <a href="{{ route('login') }}" class="btn-solid-sky w-full mt-6 text-center justify-center py-3 rounded-xl font-bold inline-block">
                        <i class="fas fa-sign-in-alt mr-2"></i> Sign In to Apply
                    </a>
                    <p class="text-center text-xs text-gray-400 mt-2">Don't have an account? <a href="{{ route('register') }}" class="text-[#0a7aa8]">Register here</a></p>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
