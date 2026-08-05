@extends('layouts.app')
@section('title', 'Create Vacancy')
@section('content')

@php
    $user = Auth::user();
    $userDepartment = $user->department;
    $isAdmin = $user->user_type === 'admin';
@endphp

<section class="max-w-5xl mx-auto px-4 sm:px-6 py-6 sm:py-8">
    <div class="bg-gradient-to-r from-[#0b3b5a] to-[#0a7aa8] rounded-2xl p-6 sm:p-8 text-white mb-8">
        <h1 class="text-2xl sm:text-3xl font-extrabold">
            <i class="fas fa-plus-circle mr-3"></i> Create New Vacancy
        </h1>
        <p class="text-white/80 mt-2 text-sm">
            @if(!$isAdmin && $userDepartment)
                Creating for: <strong>{{ $userDepartment }}</strong>
            @else
                Post a new job opening for TNT Construction & Trading PLC
            @endif
        </p>
    </div>

    @if(!$isAdmin && !$userDepartment)
        <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-4 rounded-xl mb-6 text-sm">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <strong>No department assigned!</strong> You cannot create vacancies without a department. 
            Contact Admin to assign your department.
        </div>
        <div class="text-center py-8">
            <a href="{{ route('hr.vacancies.index') }}" class="text-[#0a7aa8] font-semibold">← Back to Vacancies</a>
        </div>
    @else
    <form action="{{ route('hr.vacancies.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b flex items-center gap-3">
                <span class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-info-circle text-blue-600"></i>
                </span>
                <div>
                    <h2 class="font-bold text-gray-900">Basic Information</h2>
                    <p class="text-xs text-gray-500">Required fields for the job posting</p>
                </div>
            </div>
            <div class="p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Job Title <span class="text-red-400">*</span></label>
                        <input type="text" name="title" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none"
                            placeholder="e.g., Senior Project Engineer">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Job Category <span class="text-red-400">*</span></label>
                        <select name="job_category" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none bg-white">
                            <option value="">Select Category</option>
                            <optgroup label="Engineering & Technical">
                                <option value="civil_engineering">Civil Engineering</option>
                                <option value="construction_management">Construction Management</option>
                                <option value="project_engineering">Project Engineering</option>
                                <option value="site_engineering">Site Engineering</option>
                                <option value="office_engineering">Office Engineering</option>
                            </optgroup>
                            <optgroup label="Management & Administration">
                                <option value="executive_management">Executive Management</option>
                                <option value="project_management">Project Management</option>
                                <option value="contract_administration">Contract Administration</option>
                                <option value="human_resources">Human Resources</option>
                            </optgroup>
                            <optgroup label="HSE & Others">
                                <option value="occupational_health_safety">Occupational Health & Safety</option>
                                <option value="finance_accounting">Finance & Accounting</option>
                                <option value="equipment_logistics">Equipment & Logistics</option>
                                <option value="trade_tvet_foremen">Trade / TVET / Foremen</option>
                                <option value="it_support">IT Support</option>
                            </optgroup>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Department <span class="text-red-400">*</span>
                        </label>
                        @if($isAdmin)
                            <select name="department" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none bg-white">
                                <option value="">Select Department</option>
                                @foreach(\App\Models\Department::where('is_active', true)->orderBy('code')->get() as $dept)
                                    <option value="{{ $dept->name }}">{{ $dept->code }} - {{ $dept->name }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="text" value="{{ $userDepartment }}" readonly 
                                class="w-full border-2 border-gray-300 bg-gray-100 rounded-xl px-4 py-3 text-sm text-gray-600 cursor-not-allowed">
                            <input type="hidden" name="department" value="{{ $userDepartment }}">
                            <p class="text-xs text-gray-400 mt-1">Department is locked to your assigned department</p>
                        @endif
                    </div>
                </div>
                <!-- Rest of form fields same as before -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Duty Station Category <span class="text-red-400">*</span></label>
                        <select name="duty_station_category" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none bg-white">
                            <option value="head_office">Head Office (Addis Ababa)</option>
                            <option value="project_site">Project Site</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Duty Station <span class="text-red-400">*</span></label>
                        <input type="text" name="duty_station" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none"
                            placeholder="e.g., Bahir Dar, Addis Ababa">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Employment Type <span class="text-red-400">*</span></label>
                        <select name="employment_type" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none bg-white">
                            <option value="permanent">Permanent</option><option value="contract">Contractual</option>
                            <option value="project_based">Project-Based</option><option value="temporary">Temporary</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Positions <span class="text-red-400">*</span></label>
                        <input type="number" name="positions_count" value="1" min="1" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none">
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Min. Experience (Years) <span class="text-red-400">*</span></label>
                        <input type="number" name="min_years_experience" value="0" min="0" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Min. Education <span class="text-red-400">*</span></label>
                        <select name="min_education_level" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none bg-white">
                            <option value="">Select</option>
                            <option value="tvet_level_1">TVET Level I</option><option value="diploma">Diploma</option>
                            <option value="bsc">BSc Degree</option><option value="msc">MSc Degree</option><option value="phd">PhD</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Opening Date <span class="text-red-400">*</span></label>
                        <input type="date" name="opening_date" value="{{ date('Y-m-d') }}" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Closing Date <span class="text-red-400">*</span></label>
                        <input type="date" name="closing_date" required class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none">
                    </div>
                    <div class="flex items-end">
                        <label class="flex items-center gap-3 p-4 bg-yellow-50 rounded-xl border border-yellow-200 cursor-pointer w-full">
                            <input type="checkbox" name="construction_experience_required" value="1" class="w-5 h-5 rounded border-gray-300 text-[#0a7aa8]">
                            <span class="text-sm font-semibold text-gray-800">Construction Experience Required</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Descriptions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b flex items-center gap-3">
                <span class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-file-alt text-green-600"></i>
                </span>
                <div><h2 class="font-bold text-gray-900">Job Descriptions</h2></div>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">English Description</label>
                    <textarea name="description_en" rows="3" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none resize-y" placeholder="Job description..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Amharic Description (የስራ መግለጫ)</label>
                    <textarea name="description_am" rows="3" dir="rtl" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none resize-y text-right" placeholder="የስራ መግለጫ በአማርኛ..."></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Requirements</label>
                    <textarea name="requirements_en" rows="2" class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none resize-y" placeholder="Requirements..."></textarea>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-end gap-3">
            <a href="{{ route('hr.vacancies.index') }}" class="px-6 py-3.5 border-2 border-gray-300 text-gray-600 rounded-xl font-semibold text-sm hover:bg-gray-50 transition text-center">
                Cancel
            </a>
            <button type="submit" name="status" value="draft" class="px-6 py-3.5 border-2 border-gray-300 text-gray-600 rounded-xl font-semibold text-sm hover:bg-gray-50 transition">
                Save as Draft
            </button>
            <button type="submit" class="btn-solid-sky px-8 py-3.5 rounded-xl font-bold text-sm shadow-lg">
                <i class="fas fa-check-circle mr-2"></i> Publish Vacancy
            </button>
        </div>
    </form>
    @endif
</section>
@endsection
