@extends('layouts.app')
@section('title', 'Apply - ' . $vacancy->title)
@section('content')

<section class="max-w-3xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Apply for Position</h1>
        <p class="text-gray-500 mt-1">{{ $vacancy->title }} ({{ $vacancy->vacancy_number }})</p>
    </div>

    <!-- Vacancy Summary -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border mb-6">
        <h3 class="font-bold text-lg mb-3">Position Details</h3>
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div><span class="text-gray-500">Department:</span> <span class="font-semibold">{{ $vacancy->department }}</span></div>
            <div><span class="text-gray-500">Location:</span> <span class="font-semibold">{{ $vacancy->duty_station }}</span></div>
            <div><span class="text-gray-500">Type:</span> <span class="font-semibold">{{ ucfirst($vacancy->employment_type) }}</span></div>
            <div><span class="text-gray-500">Experience:</span> <span class="font-semibold">{{ $vacancy->min_years_experience }}+ years</span></div>
            <div><span class="text-gray-500">Deadline:</span> <span class="font-semibold text-red-500">{{ $vacancy->closing_date->format('M d, Y') }}</span></div>
            <div><span class="text-gray-500">Positions:</span> <span class="font-semibold">{{ $vacancy->positions_count }}</span></div>
        </div>
    </div>

    <!-- Applicant Summary -->
    <div class="bg-sky-50 rounded-2xl p-6 border border-sky-100 mb-6">
        <h3 class="font-bold text-lg text-[#0b3b5a] mb-3">Your Profile Summary</h3>
        <div class="grid grid-cols-2 gap-3 text-sm">
            <div><span class="text-gray-500">Name:</span> <span class="font-semibold">{{ $applicant->full_name_en }}</span></div>
            <div><span class="text-gray-500">Email:</span> <span class="font-semibold">{{ $applicant->user->email }}</span></div>
            <div><span class="text-gray-500">Phone:</span> <span class="font-semibold">{{ $applicant->user->phone }}</span></div>
            <div><span class="text-gray-500">Experience:</span> <span class="font-semibold">{{ $applicant->total_years_exp }} years</span></div>
            <div><span class="text-gray-500">Education:</span> <span class="font-semibold">{{ $applicant->educationHistories->count() }} qualifications</span></div>
            <div><span class="text-gray-500">Documents:</span> <span class="font-semibold">{{ $applicant->documents->count() }} uploaded</span></div>
        </div>
    </div>

    <!-- Application Form -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border">
        <h3 class="font-bold text-lg mb-4">Submit Application</h3>
        
        <form action="{{ route('applicant.application.store', $vacancy) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            
            <!-- Cover Letter -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Cover Letter (Optional)</label>
                <input type="file" name="cover_letter" accept=".pdf,.docx" 
                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-sky-50 file:text-[#0a7aa8] file:font-semibold">
                <p class="text-xs text-gray-400 mt-1">Accepted: PDF, DOCX (Max: 5MB)</p>
            </div>

            <!-- Declaration -->
            <div class="bg-yellow-50 rounded-xl p-4 border border-yellow-200">
                <h4 class="font-semibold text-yellow-800 mb-2">Important Declaration</h4>
                <p class="text-sm text-yellow-700 mb-3">By submitting this application, you confirm that:</p>
                <ul class="text-sm text-yellow-700 space-y-1 list-disc list-inside">
                    <li>All information provided is true and accurate</li>
                    <li>All uploaded documents are genuine</li>
                    <li>You understand false information leads to disqualification</li>
                    <li>You consent to verification of all provided information</li>
                </ul>
                <label class="flex items-start gap-3 mt-3 cursor-pointer">
                    <input type="checkbox" name="declaration_accepted" required 
                        class="mt-0.5 w-5 h-5 rounded border-gray-300 text-[#0a7aa8] focus:ring-[#0a7aa8]">
                    <span class="text-sm text-gray-700 font-semibold">
                        I accept the declaration and confirm all information is accurate
                    </span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex justify-between items-center pt-2">
                <a href="{{ route('vacancies.public.show', $vacancy) }}" 
                    class="px-6 py-3 border-2 border-gray-300 text-gray-600 rounded-xl font-semibold text-sm hover:bg-gray-50 transition">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
                <button type="submit" 
                    class="px-8 py-3 bg-[#0a7aa8] text-white rounded-xl font-bold text-sm hover:bg-[#0b5f85] transition shadow-lg shadow-sky-500/25">
                    <i class="fas fa-paper-plane mr-2"></i> Submit Application
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
