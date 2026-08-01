@extends('layouts.app')
@section('title', 'Create Vacancy')
@section('content')

<section class="max-w-5xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Create New Vacancy</h1>
        <p class="text-gray-500 mt-1">Post a new job opening for TNT Construction</p>
    </div>

    <form action="{{ route('hr.vacancies.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- Basic Information -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h2 class="font-bold text-lg text-[#0b3b5a] mb-5 flex items-center gap-2">
                <span class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center"><i class="fas fa-info-circle text-[#0a7aa8]"></i></span>
                Basic Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold mb-1.5">Job Title *</label>
                    <input type="text" name="title" required class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="e.g., Senior Project Engineer">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Job Category *</label>
                    <select name="job_category" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
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
                    <label class="block text-sm font-semibold mb-1.5">Department *</label>
                    <input type="text" name="department" required class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="e.g., Engineering Department">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Duty Station Category *</label>
                    <select name="duty_station_category" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        <option value="head_office">Head Office (Addis Ababa)</option>
                        <option value="project_site">Project Site</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Duty Station *</label>
                    <input type="text" name="duty_station" required class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="e.g., Project Site - Building">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Employment Type *</label>
                    <select name="employment_type" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        <option value="permanent">Permanent</option>
                        <option value="contract">Contractual</option>
                        <option value="project_based">Project-Based</option>
                        <option value="temporary">Temporary</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Number of Positions *</label>
                    <input type="number" name="positions_count" value="1" min="1" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Salary Type *</label>
                    <select name="salary_type" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        <option value="negotiable">Negotiable (Attractive)</option>
                        <option value="scale">As per Company Scale</option>
                        <option value="fixed">Fixed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Min. Years Experience *</label>
                    <input type="number" name="min_years_experience" value="0" min="0" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Min. Education Level *</label>
                    <select name="min_education_level" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        <option value="tvet_level_1">TVET Level I</option>
                        <option value="diploma">Diploma</option>
                        <option value="bsc">BSc Degree</option>
                        <option value="msc">MSc Degree</option>
                        <option value="phd">PhD</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Opening Date *</label>
                    <input type="date" name="opening_date" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Closing Date *</label>
                    <input type="date" name="closing_date" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div class="md:col-span-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="construction_experience_required" value="1" class="w-4 h-4 rounded border-gray-300 text-[#0a7aa8]">
                        <span class="text-sm font-semibold text-gray-700">Construction Experience Required</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Descriptions -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h2 class="font-bold text-lg text-[#0b3b5a] mb-5 flex items-center gap-2">
                <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center"><i class="fas fa-file-alt text-green-600"></i></span>
                Job Descriptions
            </h2>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-1.5">English Description</label>
                    <textarea name="description_en" rows="4" class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Detailed job description in English..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Amharic Description (የስራ መግለጫ)</label>
                    <textarea name="description_am" rows="4" class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="የስራ መግለጫ በአማርኛ..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">English Responsibilities</label>
                    <textarea name="responsibilities_en" rows="3" class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Key responsibilities..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Amharic Responsibilities (ኃላፊነቶች)</label>
                    <textarea name="responsibilities_am" rows="3" class="search-input w-full px-4 py-3 rounded-xl text-sm"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">English Requirements</label>
                    <textarea name="requirements_en" rows="3" class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Job requirements..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1.5">Amharic Requirements (መስፈርቶች)</label>
                    <textarea name="requirements_am" rows="3" class="search-input w-full px-4 py-3 rounded-xl text-sm"></textarea>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3">
            <a href="{{ route('hr.vacancies.index') }}" class="border border-gray-300 text-gray-600 px-6 py-3 rounded-xl font-semibold text-sm hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" name="status" value="draft" class="border border-gray-300 text-gray-600 px-6 py-3 rounded-xl font-semibold text-sm hover:bg-gray-50">
                Save as Draft
            </button>
            <button type="submit" class="btn-solid-sky px-8 py-3 rounded-xl font-bold text-sm">
                <i class="fas fa-check mr-2"></i> Publish Vacancy
            </button>
        </div>
    </form>
</section>
@endsection
