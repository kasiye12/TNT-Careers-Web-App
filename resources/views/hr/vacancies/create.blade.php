@extends('layouts.app')
@section('title', 'Create Vacancy')
@section('content')

<section class="max-w-5xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#0b3b5a] to-[#0a7aa8] rounded-2xl p-6 sm:p-8 text-white mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold">
            <i class="fas fa-plus-circle mr-3"></i> Create New Vacancy
        </h1>
        <p class="text-white/80 mt-2 text-sm sm:text-base">Post a new job opening for TNT Construction & Trading PLC</p>
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

    <form action="{{ route('hr.vacancies.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Section 1: Basic Information -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center gap-3">
                <span class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600"></i>
                </span>
                <div>
                    <h2 class="font-bold text-gray-900">Basic Information</h2>
                    <p class="text-xs text-gray-500">Required fields for the job posting</p>
                </div>
            </div>
            <div class="p-6 space-y-5">
                <!-- Row 1 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Job Title <span class="text-red-400">*</span>
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}" required 
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none"
                            placeholder="e.g., Senior Project Engineer">
                    </div>
                </div>
                <!-- Row 2 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Job Category <span class="text-red-400">*</span></label>
                        <select name="job_category" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none bg-white">
                            <option value="">Select Category</option>
                            <option value="executive_management">Executive Management</option>
                            <option value="project_engineering">Project Engineering</option>
                            <option value="office_engineering">Office Engineering</option>
                            <option value="occupational_health_safety">Occupational Health & Safety</option>
                            <option value="finance_accounting">Finance & Accounting</option>
                            <option value="equipment_logistics">Equipment & Logistics</option>
                            <option value="trade_tvet_foremen">Trade & TVET Foremen</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Department <span class="text-red-400">*</span></label>
                        <input type="text" name="department" value="{{ old('department') }}" required 
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none"
                            placeholder="e.g., Engineering Department">
                    </div>
                </div>
                <!-- Row 3 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Duty Station Category <span class="text-red-400">*</span></label>
                        <select name="duty_station_category" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none bg-white">
                            <option value="">Select Category</option>
                            <option value="head_office">Head Office (Addis Ababa)</option>
                            <option value="project_site">Project Site</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Duty Station <span class="text-red-400">*</span></label>
                        <input type="text" name="duty_station" value="{{ old('duty_station') }}" required 
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none"
                            placeholder="e.g., Project Site - Building">
                    </div>
                </div>
                <!-- Row 4 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Employment Type <span class="text-red-400">*</span></label>
                        <select name="employment_type" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none bg-white">
                            <option value="">Select Type</option>
                            <option value="permanent">Permanent</option>
                            <option value="contract">Contractual</option>
                            <option value="project_based">Project-Based</option>
                            <option value="temporary">Temporary</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Positions <span class="text-red-400">*</span></label>
                        <input type="number" name="positions_count" value="{{ old('positions_count', 1) }}" min="1" required 
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Salary Type <span class="text-red-400">*</span></label>
                        <select name="salary_type" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none bg-white">
                            <option value="negotiable">Negotiable (Attractive)</option>
                            <option value="scale">As per Company Scale</option>
                            <option value="fixed">Fixed</option>
                        </select>
                    </div>
                </div>
                <!-- Row 5 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Min. Experience <span class="text-red-400">*</span></label>
                        <input type="number" name="min_years_experience" value="{{ old('min_years_experience', 0) }}" min="0" required 
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Min. Education <span class="text-red-400">*</span></label>
                        <select name="min_education_level" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none bg-white">
                            <option value="">Select Level</option>
                            <option value="tvet_level_1">TVET Level I</option>
                            <option value="tvet_level_2">TVET Level II</option>
                            <option value="tvet_level_3">TVET Level III</option>
                            <option value="tvet_level_4">TVET Level IV</option>
                            <option value="tvet_level_5">TVET Level V</option>
                            <option value="diploma">Diploma</option>
                            <option value="bsc">BSc Degree</option>
                            <option value="ba">BA Degree</option>
                            <option value="msc">MSc Degree</option>
                            <option value="ma">MA Degree</option>
                            <option value="phd">PhD</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Opening Date <span class="text-red-400">*</span></label>
                        <input type="date" name="opening_date" value="{{ old('opening_date', date('Y-m-d')) }}" required 
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none">
                    </div>
                </div>
                <!-- Row 6 -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Closing Date <span class="text-red-400">*</span></label>
                        <input type="date" name="closing_date" value="{{ old('closing_date') }}" required 
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none">
                    </div>
                    <div class="flex items-end">
                        <label class="flex items-center gap-3 p-4 bg-yellow-50 rounded-xl border border-yellow-200 cursor-pointer w-full">
                            <input type="checkbox" name="construction_experience_required" value="1" 
                                class="w-5 h-5 rounded border-gray-300 text-[#0a7aa8] focus:ring-[#0a7aa8]">
                            <span class="text-sm font-semibold text-gray-800">Construction Experience Required</span>
                        </label>
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
                    <p class="text-xs text-gray-500">Detailed description in English & Amharic</p>
                </div>
            </div>
            <div class="p-6 space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">English Description</label>
                    <textarea name="description_en" rows="4" 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none resize-y"
                        placeholder="Detailed job description in English...">{{ old('description_en') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Amharic Description (የስራ መግለጫ)</label>
                    <textarea name="description_am" rows="4" dir="rtl"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none resize-y text-right"
                        placeholder="የስራ መግለጫ በአማርኛ...">{{ old('description_am') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">English Responsibilities</label>
                    <textarea name="responsibilities_en" rows="3" 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none resize-y"
                        placeholder="Key responsibilities and duties...">{{ old('responsibilities_en') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Amharic Responsibilities (ኃላፊነቶች)</label>
                    <textarea name="responsibilities_am" rows="3" dir="rtl"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none resize-y text-right"
                        placeholder="ኃላፊነቶች በአማርኛ...">{{ old('responsibilities_am') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">English Requirements</label>
                    <textarea name="requirements_en" rows="3" 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none resize-y"
                        placeholder="Required qualifications, skills, and experience...">{{ old('requirements_en') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Amharic Requirements (መስፈርቶች)</label>
                    <textarea name="requirements_am" rows="3" dir="rtl"
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none resize-y text-right"
                        placeholder="መስፈርቶች በአማርኛ...">{{ old('requirements_am') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row justify-end gap-3">
            <a href="{{ route('hr.vacancies.index') }}" 
                class="px-6 py-3.5 border-2 border-gray-300 text-gray-600 rounded-xl font-semibold text-sm hover:bg-gray-50 transition text-center">
                <i class="fas fa-times mr-2"></i> Cancel
            </a>
            <button type="submit" name="status" value="draft" 
                class="px-6 py-3.5 border-2 border-gray-300 text-gray-600 rounded-xl font-semibold text-sm hover:bg-gray-50 transition">
                <i class="fas fa-save mr-2"></i> Save as Draft
            </button>
            <button type="submit" 
                class="btn-solid-sky px-8 py-3.5 rounded-xl font-bold text-sm shadow-lg shadow-sky-500/25">
                <i class="fas fa-check-circle mr-2"></i> Publish Vacancy
            </button>
        </div>
    </form>
</section>
@endsection
