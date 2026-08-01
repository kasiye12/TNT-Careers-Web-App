@extends('layouts.app')
@section('title', 'Add Education')
@section('content')

<section class="max-w-4xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Add Education History</h1>
        <p class="text-gray-500 mt-1">Add your educational qualifications</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm">{{ session('success') }}</div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70 mb-8">
        <h3 class="font-bold text-lg text-[#0b3b5a] mb-4"><i class="fas fa-graduation-cap mr-2 text-[#0a7aa8]"></i> New Education Record</h3>
        <form action="{{ route('applicant.education.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Institution *</label>
                    <input type="text" name="institution" required class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="e.g., Addis Ababa University">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Qualification *</label>
                    <select name="qualification" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        <option value="">Select</option>
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
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Field of Study *</label>
                    <input type="text" name="field_of_study" required class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="e.g., Civil Engineering">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">CGPA (Optional)</label>
                    <input type="number" name="cgpa" step="0.01" min="0" max="4.00" class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="e.g., 3.50">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Graduation Year *</label>
                    <input type="number" name="graduation_year" required min="1950" max="{{ date('Y') + 1 }}" class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="e.g., 2020">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Certificate/Transcript (Optional)</label>
                    <input type="file" name="certificate" accept=".pdf,.jpg,.jpeg,.png" class="search-input w-full px-4 py-3 rounded-xl text-sm">
                    <p class="text-xs text-gray-400 mt-1">Max 5MB | PDF, JPG, PNG</p>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('applicant.dashboard') }}" class="border border-gray-300 text-gray-600 px-6 py-3 rounded-xl font-semibold text-sm hover:bg-gray-50 transition">Back</a>
                <button type="submit" class="btn-solid-sky px-8 py-3 rounded-xl font-bold text-sm">Add Education</button>
            </div>
        </form>
    </div>

    <!-- Existing Education Records -->
    @php $educations = Auth::user()->applicant->educationHistories ?? collect(); @endphp
    @if($educations->isNotEmpty())
        <div>
            <h3 class="font-bold text-lg text-[#0b3b5a] mb-4"><i class="fas fa-list mr-2 text-[#0a7aa8]"></i> Your Education History</h3>
            <div class="space-y-3">
                @foreach($educations as $edu)
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200/70 flex justify-between items-center">
                        <div>
                            <p class="font-semibold text-gray-900">{{ $edu->institution }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $edu->qualification_label ?? $edu->qualification }} in {{ $edu->field_of_study }}
                                @if($edu->cgpa) | CGPA: {{ $edu->cgpa }} @endif
                                | Year: {{ $edu->graduation_year }}
                            </p>
                        </div>
                        @if($edu->certificate_file_path)
                            <span class="text-xs text-green-600 font-semibold"><i class="fas fa-check-circle mr-1"></i> Uploaded</span>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Navigation -->
    <div class="mt-8 flex justify-between">
        <a href="{{ route('applicant.profile.edit') }}" class="text-gray-500 hover:text-[#0b3b5a] text-sm"><i class="fas fa-arrow-left mr-1"></i> Back to Profile</a>
        <a href="{{ route('applicant.experience.create') }}" class="text-[#0a7aa8] hover:text-[#0b3b5a] text-sm font-semibold">Add Experience <i class="fas fa-arrow-right ml-1"></i></a>
    </div>
</section>
@endsection
