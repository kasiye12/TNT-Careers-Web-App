@extends('layouts.app')
@section('title', 'Review Applications')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0b3b5a]">📝 Review Applications</h1>
            <p class="text-gray-500 mt-1">Screen and evaluate incoming job applications</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('hr.applications.shortlisted') }}" class="border border-yellow-300 text-yellow-700 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-yellow-50">
                ⭐ Shortlisted ({{ \App\Models\Application::where('status','shortlisted')->count() }})
            </a>
            <a href="{{ route('hr.applications.pipeline') }}" class="border border-gray-300 text-gray-600 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50">
                🔄 Pipeline
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if($applications->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center shadow-sm border">
            <div class="text-6xl mb-4">📭</div>
            <h3 class="text-xl font-bold text-[#0b3b5a] mb-2">No Applications Yet</h3>
            <p class="text-gray-500">New applications will appear here for review.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($applications as $app)
                <div class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md transition">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start gap-4 flex-1">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <span class="text-blue-700 font-bold text-lg">{{ substr($app->applicant->full_name_en ?? '?', 0, 1) }}</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-[#0b3b5a]">{{ $app->applicant->full_name_en ?? 'N/A' }}</h3>
                                <p class="text-sm text-gray-500">{{ $app->applicant->user->email ?? '' }} | {{ $app->applicant->user->phone ?? '' }}</p>
                                <div class="mt-2 p-3 bg-gray-50 rounded-xl">
                                    <p class="font-semibold text-sm">{{ $app->vacancy->title ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-400">{{ $app->vacancy->vacancy_number ?? '' }} | {{ $app->vacancy->department ?? '' }}</p>
                                </div>
                                <div class="flex flex-wrap gap-3 mt-2 text-sm text-gray-600">
                                    <span>{{ $app->applicant->total_years_exp ?? 0 }} yrs exp</span>
                                    <span>{{ $app->applicant->educationHistories->first()->qualification_label ?? 'N/A' }}</span>
                                    <span>Applied {{ $app->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- ACTION BUTTONS -->
                        <div class="flex flex-col gap-2 flex-shrink-0 min-w-[170px]">
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wide text-center">Actions</span>
                            
                            <!-- APPROVE: Shortlist -->
                            <form action="{{ route('hr.applications.update-status', $app) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="shortlisted">
                                <button type="submit" class="w-full px-4 py-2.5 bg-yellow-500 text-white rounded-xl text-sm font-bold hover:bg-yellow-600 transition shadow-sm">
                                    ⭐ Shortlist (Approve)
                                </button>
                            </form>

                            <!-- REJECT -->
                            <button onclick="showRejectModal('{{ $app->id }}')" 
                                class="w-full px-4 py-2.5 bg-red-50 text-red-600 rounded-xl text-sm font-bold hover:bg-red-100 transition border border-red-200">
                                ❌ Reject
                            </button>

                            <!-- View -->
                            <a href="{{ route('applicant.applications.show', $app) }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 transition text-center">
                                👁️ View Details
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $applications->links() }}</div>
    @endif
</section>

<!-- REJECT MODAL -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-xl mx-4">
        <h3 class="text-lg font-bold text-[#0b3b5a] mb-4">❌ Reject Application</h3>
        <p class="text-sm text-gray-500 mb-4">Are you sure you want to reject this application? This action can be undone by changing the status later.</p>
        <form id="rejectForm" method="POST">
            @csrf
            <input type="hidden" name="status" value="rejected">
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Reason (Optional)</label>
                <textarea name="notes" rows="2" class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="e.g., Does not meet minimum requirements..."></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="hideRejectModal()" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-xl text-sm">Cancel</button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-bold">Confirm Reject</button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectModal(appId) {
    document.getElementById('rejectForm').action = '/hr/applications/' + appId + '/status';
    document.getElementById('rejectModal').classList.remove('hidden');
}
function hideRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endsection
