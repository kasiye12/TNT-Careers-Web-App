@extends('layouts.app')
@section('title', 'Add Experience')
@section('content')

<section class="max-w-4xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Add Work Experience</h1>
        <p class="text-gray-500 mt-1">Record your professional experience</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm">{{ session('success') }}</div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70 mb-8">
        <h3 class="font-bold text-lg text-[#0b3b5a] mb-4"><i class="fas fa-briefcase mr-2 text-[#0a7aa8]"></i> New Work Experience</h3>
        <form action="{{ route('applicant.experience.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Organization Name *</label>
                    <input type="text" name="organization_name" required class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="e.g., TNT Construction">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Sector *</label>
                    <select name="sector" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        <option value="">Select</option>
                        <option value="construction">Construction</option>
                        <option value="government">Government</option>
                        <option value="consultant">Consultant</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Position Held *</label>
                    <input type="text" name="position_held" required class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="e.g., Senior Engineer">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">From Date *</label>
                    <input type="date" name="from_date" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">To Date</label>
                    <input type="date" name="to_date" id="to_date" class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
                <div class="md:col-span-2 flex flex-wrap gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_current" id="is_current" value="1" class="rounded border-gray-300 text-[#0a7aa8]">
                        <span class="text-sm text-gray-700">I currently work here</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_construction_company" value="1" class="rounded border-gray-300 text-[#0a7aa8]">
                        <span class="text-sm text-gray-700">This is a construction company</span>
                    </label>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Project Type</label>
                    <input type="text" name="project_type" class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="e.g., High-rise Building, Asphalt Road">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Key Responsibilities</label>
                    <textarea name="key_responsibilities" rows="3" class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Describe your main duties and achievements..."></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Reason for Leaving</label>
                    <input type="text" name="reason_for_leaving" class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="e.g., Career growth">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Experience Letter (Optional)</label>
                    <input type="file" name="experience_letter" accept=".pdf,.jpg,.png" class="search-input w-full px-4 py-3 rounded-xl text-sm">
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('applicant.dashboard') }}" class="border border-gray-300 text-gray-600 px-6 py-3 rounded-xl font-semibold text-sm hover:bg-gray-50 transition">Back</a>
                <button type="submit" class="btn-solid-sky px-8 py-3 rounded-xl font-bold text-sm">Add Experience</button>
            </div>
        </form>
    </div>

    <!-- Existing Experience Records -->
    @php $experiences = Auth::user()->applicant->workExperiences ?? collect(); @endphp
    @if($experiences->isNotEmpty())
        <div>
            <h3 class="font-bold text-lg text-[#0b3b5a] mb-4"><i class="fas fa-list mr-2 text-[#0a7aa8]"></i> Your Work History</h3>
            <div class="space-y-3">
                @foreach($experiences as $exp)
                    <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-200/70">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $exp->organization_name }}</p>
                                <p class="text-sm text-gray-600">{{ $exp->position_held }}</p>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $exp->from_date->format('M Y') }} - 
                                    {{ $exp->is_current ? 'Present' : ($exp->to_date ? $exp->to_date->format('M Y') : 'N/A') }}
                                    <span class="ml-2">({{ $exp->duration ?? 'N/A' }})</span>
                                </p>
                            </div>
                            @if($exp->is_construction_company)
                                <span class="px-2 py-1 bg-sky-100 text-[#0a5f89] rounded-full text-xs font-semibold">Construction</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Navigation -->
    <div class="mt-8 flex justify-between">
        <a href="{{ route('applicant.education.create') }}" class="text-gray-500 hover:text-[#0b3b5a] text-sm"><i class="fas fa-arrow-left mr-1"></i> Back to Education</a>
        <a href="{{ route('applicant.documents') }}" class="text-[#0a7aa8] hover:text-[#0b3b5a] text-sm font-semibold">Upload Documents <i class="fas fa-arrow-right ml-1"></i></a>
    </div>
</section>

<script>
document.getElementById('is_current').addEventListener('change', function() {
    document.getElementById('to_date').disabled = this.checked;
    if (this.checked) document.getElementById('to_date').value = '';
});
</script>
@endsection
