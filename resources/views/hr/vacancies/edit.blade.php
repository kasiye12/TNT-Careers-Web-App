@extends('layouts.app')
@section('title', 'Edit Vacancy')
@section('content')

<section class="max-w-5xl mx-auto px-6 py-8">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Edit Vacancy</h1>
            <p class="text-gray-500 mt-1">{{ $vacancy->vacancy_number }} - {{ $vacancy->title }}</p>
        </div>
        <span class="px-3 py-1.5 rounded-full text-xs font-bold
            @if($vacancy->status === 'published') bg-green-100 text-green-700
            @elseif($vacancy->status === 'draft') bg-gray-100 text-gray-600
            @else bg-red-100 text-red-600 @endif">
            {{ ucfirst($vacancy->status) }}
        </span>
    </div>

    <form action="{{ route('hr.vacancies.update', $vacancy) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h2 class="font-bold text-lg mb-5"><i class="fas fa-info-circle mr-2 text-[#0a7aa8]"></i>Basic Information</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1.5">Job Title *</label>
                    <input type="text" name="title" value="{{ $vacancy->title }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Department *</label>
                    <input type="text" name="department" value="{{ $vacancy->department }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Duty Station *</label>
                    <input type="text" name="duty_station" value="{{ $vacancy->duty_station }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Employment Type *</label>
                    <select name="employment_type" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        @foreach(['permanent','contract','project_based','temporary'] as $type)
                            <option value="{{ $type }}" {{ $vacancy->employment_type == $type ? 'selected' : '' }}>{{ ucfirst(str_replace('_',' ',$type)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Positions *</label>
                    <input type="number" name="positions_count" value="{{ $vacancy->positions_count }}" min="1" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Min. Experience *</label>
                    <input type="number" name="min_years_experience" value="{{ $vacancy->min_years_experience }}" min="0" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Min. Education *</label>
                    <select name="min_education_level" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        @foreach(['tvet_level_1'=>'TVET I','diploma'=>'Diploma','bsc'=>'BSc','msc'=>'MSc','phd'=>'PhD'] as $v=>$l)
                            <option value="{{ $v }}" {{ $vacancy->min_education_level == $v ? 'selected' : '' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Closing Date *</label>
                    <input type="date" name="closing_date" value="{{ $vacancy->closing_date->format('Y-m-d') }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h2 class="font-bold text-lg mb-5"><i class="fas fa-file-alt mr-2 text-green-600"></i>Descriptions</h2>
            <div class="space-y-4">
                <div><label class="block text-sm font-semibold mb-1">English Description</label><textarea name="description_en" rows="3" class="search-input w-full px-4 py-3 rounded-xl text-sm">{{ $vacancy->description_en }}</textarea></div>
                <div><label class="block text-sm font-semibold mb-1">Amharic Description</label><textarea name="description_am" rows="3" class="search-input w-full px-4 py-3 rounded-xl text-sm">{{ $vacancy->description_am }}</textarea></div>
                <div><label class="block text-sm font-semibold mb-1">Requirements (EN)</label><textarea name="requirements_en" rows="3" class="search-input w-full px-4 py-3 rounded-xl text-sm">{{ $vacancy->requirements_en }}</textarea></div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('hr.vacancies.index') }}" class="border border-gray-300 text-gray-600 px-6 py-3 rounded-xl font-semibold text-sm hover:bg-gray-50">Cancel</a>
            <button type="submit" class="btn-solid-sky px-8 py-3 rounded-xl font-bold text-sm">Update Vacancy</button>
        </div>
    </form>
</section>
@endsection
