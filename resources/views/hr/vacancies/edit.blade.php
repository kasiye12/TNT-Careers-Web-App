@extends('layouts.app')
@section('title', 'Edit Vacancy')
@section('content')

<section class="max-w-5xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#0b3b5a] to-[#0a7aa8] rounded-2xl p-6 sm:p-8 text-white mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-extrabold">
                    <i class="fas fa-edit mr-3"></i> Edit Vacancy
                </h1>
                <p class="text-white/80 mt-2 text-sm sm:text-base">{{ $vacancy->vacancy_number }} - {{ $vacancy->title }}</p>
            </div>
            <span class="px-4 py-2 rounded-full text-sm font-bold self-start
                @if($vacancy->status === 'published') bg-green-500 text-white
                @elseif($vacancy->status === 'draft') bg-white/20 text-white
                @else bg-red-500 text-white @endif">
                {{ ucfirst($vacancy->status) }}
            </span>
        </div>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-6 text-sm">
            <p class="font-semibold mb-2">Please fix the following errors:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('hr.vacancies.update', $vacancy) }}" method="POST" class="space-y-6">
        @csrf @method('PUT')
        
        <!-- Section 1: Basic Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <span class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600"></i>
                </span>
                <div>
                    <h2 class="font-bold text-gray-900">Basic Information</h2>
                    <p class="text-xs text-gray-500">Update job posting details</p>
                </div>
            </div>
            <div class="p-6 space-y-5">
                <!-- Row 1 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Job Title <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title', $vacancy->title) }}" required 
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none">
                    </div>
                </div>
                <!-- Row 2 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Department <span class="text-red-400">*</span></label>
                        <input type="text" name="department" value="{{ old('department', $vacancy->department) }}" required 
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Duty Station <span class="text-red-400">*</span></label>
                        <input type="text" name="duty_station" value="{{ old('duty_station', $vacancy->duty_station) }}" required 
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none">
                    </div>
                </div>
                <!-- Row 3 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Employment Type <span class="text-red-400">*</span></label>
                        <select name="employment_type" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none bg-white">
                            @foreach(['permanent','contract','project_based','temporary'] as $type)
                                <option value="{{ $type }}" {{ $vacancy->employment_type == $type ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_',' ',$type)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Positions <span class="text-red-400">*</span></label>
                        <input type="number" name="positions_count" value="{{ old('positions_count', $vacancy->positions_count) }}" min="1" required 
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Min. Experience (Years) <span class="text-red-400">*</span></label>
                        <input type="number" name="min_years_experience" value="{{ old('min_years_experience', $vacancy->min_years_experience) }}" min="0" required 
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none">
                    </div>
                </div>
                <!-- Row 4 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Min. Education <span class="text-red-400">*</span></label>
                        <select name="min_education_level" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none bg-white">
                            @foreach(['tvet_level_1'=>'TVET I','tvet_level_2'=>'TVET II','tvet_level_3'=>'TVET III','tvet_level_4'=>'TVET IV','tvet_level_5'=>'TVET V','diploma'=>'Diploma','bsc'=>'BSc Degree','ba'=>'BA Degree','msc'=>'MSc Degree','ma'=>'MA Degree','phd'=>'PhD'] as $v=>$l)
                                <option value="{{ $v }}" {{ $vacancy->min_education_level == $v ? 'selected' : '' }}>{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Closing Date <span class="text-red-400">*</span></label>
                        <input type="date" name="closing_date" value="{{ old('closing_date', $vacancy->closing_date->format('Y-m-d')) }}" required 
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Job Descriptions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <span class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-alt text-green-600"></i>
                </span>
                <div>
                    <h2 class="font-bold text-gray-900">Job Descriptions</h2>
                    <p class="text-xs text-gray-500">Update descriptions in English & Amharic</p>
                </div>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">English Description</label>
                    <textarea name="description_en" rows="4" 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none resize-y"
                        placeholder="Detailed job description in English...">{{ old('description_en', $vacancy->description_en) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Amharic Description (የስራ መግለጫ)</label>
                    <textarea name="description_am" rows="4" dir="rtl"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none resize-y text-right"
                        placeholder="የስራ መግለጫ በአማርኛ...">{{ old('description_am', $vacancy->description_am) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">English Responsibilities</label>
                    <textarea name="responsibilities_en" rows="3" 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none resize-y"
                        placeholder="Key responsibilities...">{{ old('responsibilities_en', $vacancy->responsibilities_en) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Amharic Responsibilities (ኃላፊነቶች)</label>
                    <textarea name="responsibilities_am" rows="3" dir="rtl"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none resize-y text-right"
                        placeholder="ኃላፊነቶች በአማርኛ...">{{ old('responsibilities_am', $vacancy->responsibilities_am) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">English Requirements</label>
                    <textarea name="requirements_en" rows="3" 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none resize-y"
                        placeholder="Required qualifications...">{{ old('requirements_en', $vacancy->requirements_en) }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Amharic Requirements (መስፈርቶች)</label>
                    <textarea name="requirements_am" rows="3" dir="rtl"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none resize-y text-right"
                        placeholder="መስፈርቶች በአማርኛ...">{{ old('requirements_am', $vacancy->requirements_am) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-end gap-3">
            <a href="{{ route('hr.vacancies.index') }}" 
                class="px-6 py-3.5 border-2 border-gray-300 text-gray-600 rounded-xl font-semibold text-sm hover:bg-gray-50 transition text-center">
                <i class="fas fa-times mr-2"></i> Cancel
            </a>
            <button type="submit" 
                class="btn-solid-sky px-8 py-3.5 rounded-xl font-bold text-sm shadow-lg shadow-sky-500/25">
                <i class="fas fa-save mr-2"></i> Update Vacancy
            </button>
        </div>
    </form>
</section>
@endsection
