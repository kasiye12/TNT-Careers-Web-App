@extends('layouts.app')
@section('title', 'Free CV Generator')

@push('styles')
<style>
    .template-card { transition: all 0.3s ease; }
    .template-card:hover { transform: translateY(-4px); box-shadow: 0 20px 40px rgba(0,0,0,0.1); }
    .template-card.selected { border-color: #0a7aa8; background: #f0f9ff; }
</style>
@endpush

@section('content')
<section class="max-w-6xl mx-auto px-6 py-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-extrabold text-[#0b3b5a]">Free CV Generator</h1>
        <p class="text-gray-500 mt-2">Create a professional CV in minutes. Choose a template, fill your details, and download instantly.</p>
    </div>

    <!-- Template Selection -->
    <div class="mb-8">
        <h2 class="text-xl font-bold text-[#0b3b5a] mb-4">1. Choose Template</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <label class="template-card cursor-pointer bg-white rounded-2xl p-6 border-2 border-gray-200 selected">
                <input type="radio" name="template" value="modern" checked class="hidden">
                <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-xl p-4 text-white mb-3">
                    <div class="h-3 bg-white/30 rounded w-2/3 mb-2"></div>
                    <div class="h-2 bg-white/20 rounded w-1/2 mb-3"></div>
                    <div class="space-y-1.5">
                        <div class="h-1.5 bg-white/20 rounded w-full"></div>
                        <div class="h-1.5 bg-white/20 rounded w-3/4"></div>
                    </div>
                </div>
                <h3 class="font-bold text-center">Modern</h3>
                <p class="text-xs text-gray-400 text-center">Clean & contemporary</p>
            </label>
            <label class="template-card cursor-pointer bg-white rounded-2xl p-6 border-2 border-gray-200">
                <input type="radio" name="template" value="professional" class="hidden">
                <div class="bg-white rounded-xl p-4 border shadow-sm mb-3">
                    <div class="h-3 bg-gray-800 rounded w-1/3 mb-2"></div>
                    <div class="h-1 bg-gray-300 rounded w-full mb-3"></div>
                    <div class="space-y-1.5">
                        <div class="h-1.5 bg-gray-200 rounded w-full"></div>
                        <div class="h-1.5 bg-gray-200 rounded w-3/4"></div>
                    </div>
                </div>
                <h3 class="font-bold text-center">Professional</h3>
                <p class="text-xs text-gray-400 text-center">Traditional & formal</p>
            </label>
            <label class="template-card cursor-pointer bg-white rounded-2xl p-6 border-2 border-gray-200">
                <input type="radio" name="template" value="classic" class="hidden">
                <div class="bg-gray-50 rounded-xl p-4 border mb-3">
                    <div class="text-center mb-3">
                        <div class="h-3 bg-gray-800 rounded w-1/4 mx-auto mb-1"></div>
                        <div class="h-1 bg-gray-400 rounded w-1/3 mx-auto"></div>
                    </div>
                    <div class="space-y-1.5">
                        <div class="h-1.5 bg-gray-200 rounded w-full"></div>
                        <div class="h-1.5 bg-gray-200 rounded w-2/3"></div>
                    </div>
                </div>
                <h3 class="font-bold text-center">Classic</h3>
                <p class="text-xs text-gray-400 text-center">Simple & elegant</p>
            </label>
        </div>
    </div>

    <!-- CV Form -->
    <div>
        <h2 class="text-xl font-bold text-[#0b3b5a] mb-4">2. Fill Your Details</h2>
        <form id="cvForm" action="{{ route('cv.generate') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="template" value="modern" id="selectedTemplate">

            <div class="bg-white rounded-2xl p-6 shadow-sm border">
                <h3 class="font-bold text-lg mb-4"><i class="fas fa-user mr-2 text-[#0a7aa8]"></i>Personal Info</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2"><label class="block text-sm font-semibold mb-1">Full Name *</label><input type="text" name="full_name" value="{{ $applicant->full_name_en ?? '' }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                    <div><label class="block text-sm font-semibold mb-1">Email *</label><input type="email" name="email" value="{{ Auth::user()->email ?? '' }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                    <div><label class="block text-sm font-semibold mb-1">Phone *</label><input type="text" name="phone" value="{{ Auth::user()->phone ?? '' }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                    <div class="col-span-2"><label class="block text-sm font-semibold mb-1">Address</label><input type="text" name="address" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border">
                <h3 class="font-bold text-lg mb-4"><i class="fas fa-file-alt mr-2 text-green-600"></i>Professional Summary</h3>
                <textarea name="professional_summary" rows="3" class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Brief summary of your experience and career goals..."></textarea>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg"><i class="fas fa-briefcase mr-2 text-purple-600"></i>Work Experience</h3>
                    <button type="button" onclick="addExperience()" class="text-[#0a7aa8] text-sm font-semibold hover:underline"><i class="fas fa-plus mr-1"></i> Add</button>
                </div>
                <div id="experienceContainer">
                    <div class="experience-item border rounded-xl p-4 mb-3">
                        <div class="grid grid-cols-2 gap-3">
                            <input type="text" name="experience[0][company]" placeholder="Company" class="search-input w-full px-3 py-2 text-sm rounded-lg">
                            <input type="text" name="experience[0][position]" placeholder="Position" class="search-input w-full px-3 py-2 text-sm rounded-lg">
                            <input type="text" name="experience[0][from]" placeholder="From (e.g., 2020)" class="search-input w-full px-3 py-2 text-sm rounded-lg">
                            <input type="text" name="experience[0][to]" placeholder="To (e.g., 2024)" class="search-input w-full px-3 py-2 text-sm rounded-lg">
                        </div>
                        <textarea name="experience[0][description]" rows="2" placeholder="Responsibilities..." class="search-input w-full px-3 py-2 text-sm rounded-lg mt-2"></textarea>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-lg"><i class="fas fa-graduation-cap mr-2 text-yellow-600"></i>Education</h3>
                    <button type="button" onclick="addEducation()" class="text-[#0a7aa8] text-sm font-semibold hover:underline"><i class="fas fa-plus mr-1"></i> Add</button>
                </div>
                <div id="educationContainer">
                    <div class="education-item border rounded-xl p-4 mb-3">
                        <div class="grid grid-cols-2 gap-3">
                            <input type="text" name="education[0][institution]" placeholder="Institution" class="search-input w-full px-3 py-2 text-sm rounded-lg">
                            <input type="text" name="education[0][degree]" placeholder="Degree" class="search-input w-full px-3 py-2 text-sm rounded-lg">
                            <input type="text" name="education[0][field]" placeholder="Field of Study" class="search-input w-full px-3 py-2 text-sm rounded-lg">
                            <input type="text" name="education[0][year]" placeholder="Year" class="search-input w-full px-3 py-2 text-sm rounded-lg">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-6 shadow-sm border">
                <h3 class="font-bold text-lg mb-4"><i class="fas fa-cogs mr-2 text-red-600"></i>Skills</h3>
                <textarea name="skills" rows="2" class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Project Management, AutoCAD, Team Leadership..."></textarea>
            </div>

            <div class="text-center">
                <button type="submit" class="btn-solid-sky text-lg px-12 py-4 rounded-xl font-bold shadow-lg">
                    <i class="fas fa-download mr-2"></i> Generate & Download CV (PDF)
                </button>
            </div>
        </form>
    </div>
</section>

<script>
let expCount = 1, eduCount = 1;

document.querySelectorAll('.template-card').forEach(card => {
    card.addEventListener('click', function() {
        document.querySelectorAll('.template-card').forEach(c => c.classList.remove('selected'));
        this.classList.add('selected');
        document.getElementById('selectedTemplate').value = this.querySelector('input').value;
    });
});

function addExperience() {
    document.getElementById('experienceContainer').insertAdjacentHTML('beforeend', `
        <div class="experience-item border rounded-xl p-4 mb-3">
            <button type="button" onclick="this.parentElement.remove()" class="float-right text-red-400 text-xs hover:text-red-600"><i class="fas fa-times"></i></button>
            <div class="grid grid-cols-2 gap-3">
                <input type="text" name="experience[${expCount}][company]" placeholder="Company" class="search-input w-full px-3 py-2 text-sm rounded-lg">
                <input type="text" name="experience[${expCount}][position]" placeholder="Position" class="search-input w-full px-3 py-2 text-sm rounded-lg">
                <input type="text" name="experience[${expCount}][from]" placeholder="From" class="search-input w-full px-3 py-2 text-sm rounded-lg">
                <input type="text" name="experience[${expCount}][to]" placeholder="To" class="search-input w-full px-3 py-2 text-sm rounded-lg">
            </div>
            <textarea name="experience[${expCount}][description]" rows="2" placeholder="Responsibilities..." class="search-input w-full px-3 py-2 text-sm rounded-lg mt-2"></textarea>
        </div>`);
    expCount++;
}

function addEducation() {
    document.getElementById('educationContainer').insertAdjacentHTML('beforeend', `
        <div class="education-item border rounded-xl p-4 mb-3">
            <button type="button" onclick="this.parentElement.remove()" class="float-right text-red-400 text-xs hover:text-red-600"><i class="fas fa-times"></i></button>
            <div class="grid grid-cols-2 gap-3">
                <input type="text" name="education[${eduCount}][institution]" placeholder="Institution" class="search-input w-full px-3 py-2 text-sm rounded-lg">
                <input type="text" name="education[${eduCount}][degree]" placeholder="Degree" class="search-input w-full px-3 py-2 text-sm rounded-lg">
                <input type="text" name="education[${eduCount}][field]" placeholder="Field" class="search-input w-full px-3 py-2 text-sm rounded-lg">
                <input type="text" name="education[${eduCount}][year]" placeholder="Year" class="search-input w-full px-3 py-2 text-sm rounded-lg">
            </div>
        </div>`);
    eduCount++;
}
</script>
@endsection
