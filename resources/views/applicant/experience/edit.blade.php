@extends('layouts.app')
@section('title', 'Edit Experience')
@section('content')

<section class="max-w-2xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-extrabold text-[#0b3b5a] mb-6">Edit Work Experience</h1>
    <div class="bg-white rounded-2xl p-6 shadow-sm border">
        <form action="{{ route('applicant.experience.update', $experience) }}" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold mb-1">Organization *</label>
                <input type="text" name="organization_name" value="{{ $experience->organization_name }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Position *</label>
                    <input type="text" name="position_held" value="{{ $experience->position_held }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Sector</label>
                    <select name="sector" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        <option value="construction" {{ $experience->sector=='construction'?'selected':'' }}>Construction</option>
                        <option value="government" {{ $experience->sector=='government'?'selected':'' }}>Government</option>
                        <option value="consultant" {{ $experience->sector=='consultant'?'selected':'' }}>Consultant</option>
                        <option value="other" {{ $experience->sector=='other'?'selected':'' }}>Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">From Date *</label>
                    <input type="date" name="from_date" value="{{ $experience->from_date->format('Y-m-d') }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">To Date</label>
                    <input type="date" name="to_date" value="{{ $experience->to_date ? $experience->to_date->format('Y-m-d') : '' }}" class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
            </div>
            <div class="flex gap-4">
                <label class="flex items-center gap-2"><input type="checkbox" name="is_current" value="1" {{ $experience->is_current?'checked':'' }} class="rounded"> Currently work here</label>
                <label class="flex items-center gap-2"><input type="checkbox" name="is_construction_company" value="1" {{ $experience->is_construction_company?'checked':'' }} class="rounded"> Construction company</label>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('applicant.experience.create') }}" class="border border-gray-300 text-gray-600 px-6 py-3 rounded-xl font-semibold text-sm">Cancel</a>
                <button type="submit" class="btn-solid-sky px-8 py-3 rounded-xl font-bold text-sm">Update Experience</button>
            </div>
        </form>
    </div>
</section>
@endsection
