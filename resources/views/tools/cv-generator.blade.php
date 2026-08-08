@extends('layouts.app')
@section('title', 'Professional CV Generator')

@push('styles')
<style>
    .template-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .template-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    }
    .template-card.selected {
        border-color: #0a7aa8;
        background: #f0f9ff;
    }
    .form-section {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 24px;
        margin-bottom: 20px;
        transition: all 0.2s;
    }
    .form-section:hover {
        border-color: #0a7aa8;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
</style>
@endpush

@section('content')
<section class="max-w-6xl mx-auto px-4 sm:px-6 py-8">
    <!-- Header -->
    <div class="text-center mb-10">
        <h1 class="text-3xl font-extrabold text-[#0b3b5a]">
            <i class="fas fa-file-alt text-[#0a7aa8] mr-3"></i> Professional CV Generator
        </h1>
        <p class="text-gray-500 mt-2 text-lg">Create a stunning CV in minutes. Choose a template, fill your details, download as PDF.</p>
    </div>

    <!-- Template Selection -->
    <div class="mb-10">
        <h2 class="text-xl font-bold text-[#0b3b5a] mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-palette text-purple-600 text-sm"></i>
            </span>
            Choose Template
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Modern Template -->
            <label class="template-card selected bg-white rounded-2xl p-6 border-2 border-gray-200" onclick="selectTemplate(this, 'modern')">
                <input type="radio" name="template" value="modern" checked class="hidden">
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl p-6 text-white mb-4">
                    <div class="h-2 bg-white/30 rounded w-2/3 mb-3"></div>
                    <div class="h-2 bg-white/20 rounded w-1/2 mb-4"></div>
                    <div class="space-y-2">
                        <div class="h-1.5 bg-white/20 rounded w-full"></div>
                        <div class="h-1.5 bg-white/20 rounded w-3/4"></div>
                        <div class="h-1.5 bg-white/20 rounded w-1/2"></div>
                    </div>
                </div>
                <h3 class="font-bold text-center text-gray-900">Modern</h3>
                <p class="text-xs text-gray-400 text-center mt-1">Clean & contemporary design</p>
            </label>

            <!-- Professional Template -->
            <label class="template-card bg-white rounded-2xl p-6 border-2 border-gray-200" onclick="selectTemplate(this, 'professional')">
                <input type="radio" name="template" value="professional" class="hidden">
                <div class="bg-white rounded-xl p-6 border shadow-sm mb-4">
                    <div class="h-3 bg-gray-800 rounded w-1/3 mb-3"></div>
                    <div class="h-1 bg-gray-300 rounded w-full mb-4"></div>
                    <div class="space-y-2">
                        <div class="h-1.5 bg-gray-200 rounded w-full"></div>
                        <div class="h-1.5 bg-gray-200 rounded w-3/4"></div>
                        <div class="h-1.5 bg-gray-200 rounded w-1/2"></div>
                    </div>
                </div>
                <h3 class="font-bold text-center text-gray-900">Professional</h3>
                <p class="text-xs text-gray-400 text-center mt-1">Traditional & formal layout</p>
            </label>

            <!-- Classic Template -->
            <label class="template-card bg-white rounded-2xl p-6 border-2 border-gray-200" onclick="selectTemplate(this, 'classic')">
                <input type="radio" name="template" value="classic" class="hidden">
                <div class="bg-gray-50 rounded-xl p-6 border mb-4">
                    <div class="text-center mb-4">
                        <div class="h-3 bg-gray-800 rounded w-1/4 mx-auto mb-1"></div>
                        <div class="h-1 bg-gray-400 rounded w-1/3 mx-auto"></div>
                    </div>
                    <div class="space-y-2">
                        <div class="h-1.5 bg-gray-200 rounded w-full"></div>
                        <div class="h-1.5 bg-gray-200 rounded w-2/3"></div>
                        <div class="h-1.5 bg-gray-200 rounded w-1/2"></div>
                    </div>
                </div>
                <h3 class="font-bold text-center text-gray-900">Classic</h3>
                <p class="text-xs text-gray-400 text-center mt-1">Simple & elegant style</p>
            </label>
        </div>
    </div>

    <!-- CV Form -->
    <form id="cvForm" action="{{ route('cv.generate') }}" method="POST">
        @csrf
        <input type="hidden" name="template" value="modern" id="selectedTemplate">

        <!-- Personal Information -->
        <div class="form-section">
            <div class="flex items-center gap-3 mb-4">
                <span class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user text-blue-600 text-sm"></i>
                </span>
                <h3 class="font-bold text-gray-900">Personal Information</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Full Name *</label>
                    <input type="text" name="full_name" value="{{ $applicant->full_name_en ?? '' }}" required 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none"
                        placeholder="Your full name">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Email *</label>
                    <input type="email" name="email" value="{{ Auth::user()->email ?? '' }}" required 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Phone *</label>
                    <input type="text" name="phone" value="{{ Auth::user()->phone ?? '' }}" required 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Professional Title</label>
                    <input type="text" name="title" placeholder="e.g., Senior Civil Engineer" 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Address</label>
                    <input type="text" name="address" placeholder="City, Country" 
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none">
                </div>
            </div>
        </div>

        <!-- Professional Summary -->
        <div class="form-section">
            <div class="flex items-center gap-3 mb-4">
                <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file-alt text-green-600 text-sm"></i>
                </span>
                <h3 class="font-bold text-gray-900">Professional Summary</h3>
            </div>
            <textarea name="professional_summary" rows="3" 
                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] focus:ring-4 focus:ring-sky-100 transition outline-none resize-y"
                placeholder="Brief overview of your experience, skills, and career goals..."></textarea>
        </div>

        <!-- Work Experience -->
        <div class="form-section">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-briefcase text-purple-600 text-sm"></i>
                    </span>
                    <h3 class="font-bold text-gray-900">Work Experience</h3>
                </div>
                <button type="button" onclick="addExperience()" 
                    class="text-[#0a7aa8] text-sm font-semibold hover:underline">
                    <i class="fas fa-plus-circle mr-1"></i> Add Experience
                </button>
            </div>
            <div id="experienceContainer">
                <div class="experience-item bg-gray-50 rounded-xl p-4 border mb-3">
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" name="experience[0][company]" placeholder="Company Name" 
                            class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-[#0a7aa8] transition outline-none">
                        <input type="text" name="experience[0][position]" placeholder="Job Title" 
                            class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-[#0a7aa8] transition outline-none">
                        <input type="text" name="experience[0][from]" placeholder="From (e.g., Jan 2020)" 
                            class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-[#0a7aa8] transition outline-none">
                        <input type="text" name="experience[0][to]" placeholder="To (e.g., Present)" 
                            class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-[#0a7aa8] transition outline-none">
                    </div>
                    <textarea name="experience[0][description]" rows="2" placeholder="Key responsibilities & achievements..." 
                        class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm mt-2 focus:border-[#0a7aa8] transition outline-none resize-y"></textarea>
                </div>
            </div>
        </div>

        <!-- Education -->
        <div class="form-section">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <span class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-graduation-cap text-yellow-600 text-sm"></i>
                    </span>
                    <h3 class="font-bold text-gray-900">Education</h3>
                </div>
                <button type="button" onclick="addEducation()" 
                    class="text-[#0a7aa8] text-sm font-semibold hover:underline">
                    <i class="fas fa-plus-circle mr-1"></i> Add Education
                </button>
            </div>
            <div id="educationContainer">
                <div class="education-item bg-gray-50 rounded-xl p-4 border mb-3">
                    <div class="grid grid-cols-2 gap-3">
                        <input type="text" name="education[0][institution]" placeholder="Institution" 
                            class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-[#0a7aa8] transition outline-none">
                        <input type="text" name="education[0][degree]" placeholder="Degree (e.g., BSc)" 
                            class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-[#0a7aa8] transition outline-none">
                        <input type="text" name="education[0][field]" placeholder="Field of Study" 
                            class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-[#0a7aa8] transition outline-none">
                        <input type="text" name="education[0][year]" placeholder="Graduation Year" 
                            class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-[#0a7aa8] transition outline-none">
                    </div>
                </div>
            </div>
        </div>

        <!-- Skills -->
        <div class="form-section">
            <div class="flex items-center gap-3 mb-4">
                <span class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cogs text-red-600 text-sm"></i>
                </span>
                <h3 class="font-bold text-gray-900">Skills & Languages</h3>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Skills (comma separated)</label>
                    <textarea name="skills" rows="2" placeholder="Project Management, AutoCAD, Leadership..."
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] transition outline-none resize-y"></textarea>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Languages</label>
                    <textarea name="languages" rows="2" placeholder="Amharic (Native), English (Fluent)..."
                        class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:border-[#0a7aa8] transition outline-none resize-y"></textarea>
                </div>
            </div>
        </div>

        <!-- Submit -->
        <div class="text-center">
            <button type="submit" 
                class="px-10 py-4 bg-[#0a7aa8] text-white rounded-2xl font-bold text-lg hover:bg-[#0b5f85] transition shadow-xl shadow-sky-500/25 hover:shadow-sky-500/40">
                <i class="fas fa-download mr-2"></i> Generate & Download CV (PDF)
            </button>
            <p class="text-xs text-gray-400 mt-3">Your CV will be generated as a professional PDF document</p>
        </div>
    </form>
</section>

<script>
let expCount = 1;
let eduCount = 1;

function selectTemplate(el, template) {
    document.querySelectorAll('.template-card').forEach(c => c.classList.remove('selected'));
    el.classList.add('selected');
    document.getElementById('selectedTemplate').value = template;
}

function addExperience() {
    const html = `
        <div class="experience-item bg-gray-50 rounded-xl p-4 border mb-3">
            <button type="button" onclick="this.parentElement.remove()" class="float-right text-red-400 hover:text-red-600 text-xs mb-2">✕ Remove</button>
            <div class="grid grid-cols-2 gap-3">
                <input type="text" name="experience[${expCount}][company]" placeholder="Company Name" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-[#0a7aa8] outline-none">
                <input type="text" name="experience[${expCount}][position]" placeholder="Job Title" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-[#0a7aa8] outline-none">
                <input type="text" name="experience[${expCount}][from]" placeholder="From" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-[#0a7aa8] outline-none">
                <input type="text" name="experience[${expCount}][to]" placeholder="To" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-[#0a7aa8] outline-none">
            </div>
            <textarea name="experience[${expCount}][description]" rows="2" placeholder="Responsibilities..." class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm mt-2 focus:border-[#0a7aa8] outline-none resize-y"></textarea>
        </div>`;
    document.getElementById('experienceContainer').insertAdjacentHTML('beforeend', html);
    expCount++;
}

function addEducation() {
    const html = `
        <div class="education-item bg-gray-50 rounded-xl p-4 border mb-3">
            <button type="button" onclick="this.parentElement.remove()" class="float-right text-red-400 hover:text-red-600 text-xs mb-2">✕ Remove</button>
            <div class="grid grid-cols-2 gap-3">
                <input type="text" name="education[${eduCount}][institution]" placeholder="Institution" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-[#0a7aa8] outline-none">
                <input type="text" name="education[${eduCount}][degree]" placeholder="Degree" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-[#0a7aa8] outline-none">
                <input type="text" name="education[${eduCount}][field]" placeholder="Field of Study" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-[#0a7aa8] outline-none">
                <input type="text" name="education[${eduCount}][year]" placeholder="Year" class="w-full border-2 border-gray-200 rounded-lg px-3 py-2 text-sm focus:border-[#0a7aa8] outline-none">
            </div>
        </div>`;
    document.getElementById('educationContainer').insertAdjacentHTML('beforeend', html);
    eduCount++;
}
</script>
@endsection
