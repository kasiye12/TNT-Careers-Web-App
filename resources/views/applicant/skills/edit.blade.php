@extends('layouts.app')
@section('title', 'Skills & Additional Info')
@section('content')

<section class="max-w-4xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Skills & Additional Information</h1>
        <p class="text-gray-500 mt-1">Add your skills, languages, and certifications</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl p-8 shadow-sm border">
        <form action="{{ route('applicant.skills.update') }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            <div>
                <label class="block text-sm font-bold text-[#0b3b5a] mb-2">
                    <i class="fas fa-user-tag mr-2 text-[#0a7aa8]"></i> Professional Title
                </label>
                <input type="text" name="professional_title" value="{{ $applicant->professional_title }}" 
                    class="search-input w-full px-4 py-3 rounded-xl text-sm" 
                    placeholder="e.g., Senior Civil Engineer, Project Manager, Safety Officer">
            </div>

            <div>
                <label class="block text-sm font-bold text-[#0b3b5a] mb-2">
                    <i class="fas fa-cogs mr-2 text-[#0a7aa8]"></i> Skills
                </label>
                <textarea name="skills" rows="4" class="search-input w-full px-4 py-3 rounded-xl text-sm"
                    placeholder="e.g., AutoCAD, Project Management, Structural Analysis, Team Leadership, Quality Control, Site Supervision, MS Project, Primavera P6">{{ $applicant->skills }}</textarea>
                <p class="text-xs text-gray-400 mt-1">Separate skills with commas. Be specific about your technical and soft skills.</p>
            </div>

            <div>
                <label class="block text-sm font-bold text-[#0b3b5a] mb-2">
                    <i class="fas fa-language mr-2 text-[#0a7aa8]"></i> Languages
                </label>
                <textarea name="languages" rows="2" class="search-input w-full px-4 py-3 rounded-xl text-sm"
                    placeholder="e.g., Amharic (Native), English (Fluent), Afan Oromo (Intermediate)">{{ $applicant->languages }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-[#0b3b5a] mb-2">
                    <i class="fas fa-certificate mr-2 text-[#0a7aa8]"></i> Certifications & Licenses
                </label>
                <textarea name="certifications" rows="3" class="search-input w-full px-4 py-3 rounded-xl text-sm"
                    placeholder="e.g., PMP Certified, EAEA Registered Engineer, NEBOSH, AutoCAD Certified Professional">{{ $applicant->certifications }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-[#0b3b5a] mb-2">
                    <i class="fab fa-linkedin mr-2 text-[#0a7aa8]"></i> LinkedIn Profile URL
                </label>
                <input type="url" name="linkedin_url" value="{{ $applicant->linkedin_url }}" 
                    class="search-input w-full px-4 py-3 rounded-xl text-sm" 
                    placeholder="https://linkedin.com/in/yourprofile">
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <a href="{{ route('applicant.dashboard') }}" class="border border-gray-300 text-gray-600 px-6 py-3 rounded-xl font-semibold text-sm hover:bg-gray-50">Cancel</a>
                <button type="submit" class="btn-solid-sky px-8 py-3 rounded-xl font-bold text-sm">Save Skills</button>
            </div>
        </form>
    </div>
</section>
@endsection
