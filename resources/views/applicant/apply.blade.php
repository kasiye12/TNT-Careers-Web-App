@extends('layouts.app')
@section('title', 'Apply - ' . $vacancy->title)
@section('content')
<section class="max-w-3xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-extrabold text-[#0b3b5a] mb-2">Apply for Position</h1>
    <p class="text-gray-500 mb-6">{{ $vacancy->title }} ({{ $vacancy->vacancy_number }})</p>
    
    <div class="bg-sky-50 rounded-2xl p-4 mb-6 text-sm">
        <p><strong>Applicant:</strong> {{ $applicant->full_name_en }}</p>
        <p><strong>Experience:</strong> {{ $applicant->total_years_exp }} years | <strong>Education:</strong> {{ $applicant->educationHistories->count() }} qualifications</p>
    </div>
    
    <form action="{{ route('applicant.application.store', $vacancy) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl p-6 shadow-sm border space-y-4">
        @csrf
        <div><label class="block text-sm font-semibold mb-1">Cover Letter (Optional)</label><input type="file" name="cover_letter" accept=".pdf,.docx" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
        <div class="bg-yellow-50 rounded-xl p-4 text-sm">
            <p class="font-semibold text-yellow-800">Declaration</p>
            <p class="text-yellow-700">I confirm all information is accurate and genuine.</p>
            <label class="flex items-center gap-2 mt-2"><input type="checkbox" name="declaration_accepted" required class="rounded"> I accept the declaration</label>
        </div>
        <div class="flex justify-end gap-3">
            <a href="{{ route('vacancies.public.show', $vacancy) }}" class="border border-gray-300 text-gray-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50">Cancel</a>
            <button type="submit" class="btn-solid-sky px-8 py-3 rounded-xl font-bold">Submit Application</button>
        </div>
    </form>
</section>
@endsection
