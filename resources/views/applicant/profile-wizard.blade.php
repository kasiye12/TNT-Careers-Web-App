@extends('layouts.app')

@section('title', 'Complete Your Profile')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Welcome to TNT Construction!</h2>
                    <p class="text-lg text-gray-600 mt-2">Let's set up your profile to start applying for positions</p>
                </div>

                <!-- Progress Steps -->
                <div class="mb-8">
                    @php
                        $applicant = Auth::user()->applicant;
                        $step = 1;
                        if ($applicant && $applicant->first_name_en) $step = 2;
                        if ($applicant && $applicant->educationHistories->isNotEmpty()) $step = 3;
                        if ($applicant && $applicant->workExperiences->isNotEmpty()) $step = 4;
                        if ($applicant && $applicant->documents->where('document_type', 'cv')->isNotEmpty()) $step = 5;
                    @endphp
                    
                    <div class="flex items-center justify-center">
                        @foreach(['Personal Info', 'Education', 'Experience', 'Documents', 'Complete'] as $index => $label)
                            <div class="flex items-center">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $index + 1 <= $step ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }} font-bold">
                                    @if($index + 1 < $step)
                                        ✓
                                    @else
                                        {{ $index + 1 }}
                                    @endif
                                </div>
                                <span class="ml-2 text-sm {{ $index + 1 <= $step ? 'text-blue-600 font-medium' : 'text-gray-500' }}">{{ $label }}</span>
                                @if($index < 4)
                                    <div class="w-12 h-0.5 mx-2 {{ $index + 1 < $step ? 'bg-blue-600' : 'bg-gray-200' }}"></div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Quick Action Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <a href="{{ $applicant ? route('applicant.profile.edit') : route('applicant.profile.create') }}" 
                       class="block p-6 bg-white border-2 rounded-lg hover:border-blue-500 transition-colors {{ $applicant && $applicant->first_name_en ? 'border-green-300 bg-green-50' : 'border-gray-200' }}">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold">Personal Information</h3>
                            @if($applicant && $applicant->first_name_en)
                                <span class="text-green-500">✓</span>
                            @endif
                        </div>
                        <p class="text-gray-600 text-sm">Your name, contact details, and address information</p>
                    </a>

                    <a href="{{ route('applicant.education.create') }}" 
                       class="block p-6 bg-white border-2 rounded-lg hover:border-blue-500 transition-colors {{ $applicant && $applicant->educationHistories->isNotEmpty() ? 'border-green-300 bg-green-50' : 'border-gray-200' }}">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold">Education History</h3>
                            @if($applicant && $applicant->educationHistories->isNotEmpty())
                                <span class="text-green-500">✓</span>
                            @endif
                        </div>
                        <p class="text-gray-600 text-sm">Add your degrees, diplomas, and certifications</p>
                    </a>

                    <a href="{{ route('applicant.experience.create') }}" 
                       class="block p-6 bg-white border-2 rounded-lg hover:border-blue-500 transition-colors {{ $applicant && $applicant->workExperiences->isNotEmpty() ? 'border-green-300 bg-green-50' : 'border-gray-200' }}">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold">Work Experience</h3>
                            @if($applicant && $applicant->workExperiences->isNotEmpty())
                                <span class="text-green-500">✓</span>
                            @endif
                        </div>
                        <p class="text-gray-600 text-sm">Record your professional experience and projects</p>
                    </a>

                    <a href="{{ route('applicant.documents') }}" 
                       class="block p-6 bg-white border-2 rounded-lg hover:border-blue-500 transition-colors {{ $applicant && $applicant->documents->where('document_type', 'cv')->isNotEmpty() ? 'border-green-300 bg-green-50' : 'border-gray-200' }}">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-semibold">Upload Documents</h3>
                            @if($applicant && $applicant->documents->where('document_type', 'cv')->isNotEmpty())
                                <span class="text-green-500">✓</span>
                            @endif
                        </div>
                        <p class="text-gray-600 text-sm">Upload CV, certificates, and identification documents</p>
                    </a>
                </div>

                <div class="mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        All fields marked with * are required. Your information is kept confidential and secure.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
