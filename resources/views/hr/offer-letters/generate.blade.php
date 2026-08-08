@extends('layouts.app')
@section('title', 'Generate Offer Letter')
@section('content')

<section class="max-w-3xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">
            <i class="fas fa-file-contract text-blue-600 mr-2"></i> Generate Offer Letter
        </h1>
        <p class="text-gray-500 mt-1">For: {{ $application->applicant->full_name_en }}</p>
    </div>

    <div class="bg-sky-50 rounded-2xl p-4 mb-6 text-sm">
        <p><strong>Position:</strong> {{ $application->vacancy->title }}</p>
        <p><strong>Department:</strong> {{ $application->vacancy->department }}</p>
        <p><strong>Vacancy Ref:</strong> {{ $application->vacancy->vacancy_number }}</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-sm border">
        <form action="{{ route('hr.offer-letters.store') }}" method="POST" class="space-y-5">
            @csrf
            <input type="hidden" name="application_id" value="{{ $application->id }}">

            <div class="grid grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Position Title *</label>
                    <input type="text" name="position_title" value="{{ $application->vacancy->title }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Department *</label>
                    <input type="text" name="department" value="{{ $application->vacancy->department }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Duty Station *</label>
                    <input type="text" name="duty_station" value="{{ $application->vacancy->duty_station }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Salary Amount (ETB) *</label>
                    <input type="number" name="salary_amount" step="0.01" required class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="e.g., 25000">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Currency</label>
                    <select name="salary_currency" class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        <option value="ETB">ETB</option>
                        <option value="USD">USD</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Reporting Date *</label>
                    <input type="date" name="reporting_date" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Offer Expiry Date *</label>
                    <input type="date" name="offer_expiry_date" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Benefits & Additional Terms</label>
                    <textarea name="benefits" rows="3" class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Housing allowance, transportation, medical coverage, etc."></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ url()->previous() }}" class="border border-gray-300 text-gray-600 px-6 py-3 rounded-xl font-semibold text-sm">Cancel</a>
                <button type="submit" class="btn-solid-sky px-8 py-3 rounded-xl font-bold text-sm">
                    <i class="fas fa-file-pdf mr-2"></i> Generate Offer Letter
                </button>
            </div>
        </form>
    </div>
</section>
@endsection
