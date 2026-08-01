@extends('layouts.app')
@section('title', 'Edit Education')
@section('content')

<section class="max-w-2xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-extrabold text-[#0b3b5a] mb-6">Edit Education Record</h1>
    <div class="bg-white rounded-2xl p-6 shadow-sm border">
        <form action="{{ route('applicant.education.update', $education) }}" method="POST" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-sm font-semibold mb-1">Institution *</label>
                <input type="text" name="institution" value="{{ $education->institution }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Qualification *</label>
                    <select name="qualification" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        <option value="diploma" {{ $education->qualification=='diploma'?'selected':'' }}>Diploma</option>
                        <option value="bsc" {{ $education->qualification=='bsc'?'selected':'' }}>BSc Degree</option>
                        <option value="msc" {{ $education->qualification=='msc'?'selected':'' }}>MSc Degree</option>
                        <option value="phd" {{ $education->qualification=='phd'?'selected':'' }}>PhD</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Field of Study *</label>
                    <input type="text" name="field_of_study" value="{{ $education->field_of_study }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">CGPA</label>
                    <input type="number" name="cgpa" value="{{ $education->cgpa }}" step="0.01" min="0" max="4" class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Graduation Year *</label>
                    <input type="number" name="graduation_year" value="{{ $education->graduation_year }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('applicant.education.create') }}" class="border border-gray-300 text-gray-600 px-6 py-3 rounded-xl font-semibold text-sm">Cancel</a>
                <button type="submit" class="btn-solid-sky px-8 py-3 rounded-xl font-bold text-sm">Update Education</button>
            </div>
        </form>
    </div>
</section>
@endsection
