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
                <i class="fas fa-star mr-2"></i> Shortlisted ({{ \App\Models\Application::where('status','shortlisted')->count() }})
            </a>
            <a href="{{ route('hr.applications.pipeline') }}" class="border border-gray-300 text-gray-600 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50">
                <i class="fas fa-sitemap mr-2"></i> Pipeline
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm">{{ session('success') }}</div>
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
                        <!-- Candidate Info -->
                        <div class="flex items-start gap-4 flex-1">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <span class="text-blue-700 font-bold text-lg">{{ substr($app->applicant->full_name_en ?? '?', 0, 1) }}</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-[#0b3b5a]">{{ $app->applicant->full_name_en ?? 'N/A' }}</h3>
                                <p class="text-sm text-gray-500">{{ $app->applicant->user->email ?? '' }} | {{ $app->applicant->user->phone ?? '' }}</p>
                                
                                <!-- Position Info -->
                                <div class="mt-2 p-3 bg-gray-50 rounded-xl">
                                    <p class="font-semibold text-sm">{{ $app->vacancy->title }}</p>
                                    <p class="text-xs text-gray-400">{{ $app->vacancy->vacancy_number }} | {{ $app->vacancy->department }}</p>
                                </div>
                                
                                <!-- Candidate Details -->
                                <div class="flex flex-wrap gap-3 mt-2 text-sm text-gray-600">
                                    <span><i class="fas fa-clock mr-1 text-gray-400"></i> {{ $app->applicant->total_years_exp ?? 0 }} yrs exp</span>
                                    <span><i class="fas fa-graduation-cap mr-1 text-gray-400"></i> 
                                        {{ $app->applicant->educationHistories->first()?->qualification_label ?? 'N/A' }}
                                    </span>
                                    <span><i class="fas fa-venus-mars mr-1 text-gray-400"></i> {{ ucfirst($app->applicant->gender ?? 'N/A') }}</span>
                                    <span><i class="far fa-calendar mr-1 text-gray-400"></i> Applied {{ $app->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- ACTION BUTTONS -->
                        <div class="flex flex-col gap-2 flex-shrink-0 min-w-[180px]">
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wide text-center">Actions</span>
                            
                            <!-- SHORTLIST BUTTON -->
                            <form action="{{ route('hr.applications.update-status', $app) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="shortlisted">
                                <input type="hidden" name="notes" value="Candidate meets requirements - shortlisted">
                                <button type="submit" 
                                    class="w-full px-4 py-2.5 bg-yellow-500 text-white rounded-xl text-sm font-bold hover:bg-yellow-600 transition shadow-sm flex items-center justify-center gap-2">
                                    <i class="fas fa-star"></i> ⭐ Shortlist
                                </button>
                            </form>

                            <!-- VERIFY DOCUMENTS -->
                            <form action="{{ route('hr.applications.update-status', $app) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="document_verified">
                                <button type="submit" 
                                    class="w-full px-4 py-2.5 bg-green-600 text-white rounded-xl text-sm font-bold hover:bg-green-700 transition shadow-sm flex items-center justify-center gap-2">
                                    <i class="fas fa-check-circle"></i> Verify Documents
                                </button>
                            </form>

                            <!-- REJECT -->
                            <button onclick="showRejectForm('{{ $app->id }}')" 
                                class="w-full px-4 py-2.5 bg-red-50 text-red-600 rounded-xl text-sm font-bold hover:bg-red-100 transition border border-red-200 flex items-center justify-center gap-2">
                                <i class="fas fa-times"></i> Reject
                            </button>

                            <!-- View Details -->
                            <a href="{{ route('applicant.applications.show', $app) }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 transition text-center">
                                <i class="fas fa-eye mr-1"></i> View Full Details
                            </a>
                        </div>
                    </div>

                    <!-- Screening Results -->
                    @php
                        $results = $app->auto_screening_results ? json_decode($app->auto_screening_results, true) : [];
                        $passed = collect($results)->where('passed', false)->isEmpty();
                    @endphp
                    @if(!empty($results))
                        <div class="mt-4 pt-4 border-t border-gray-100">
                            <p class="text-xs font-semibold text-gray-500 mb-2">Auto-Screening Results:</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($results as $result)
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $result['passed'] ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
                                        {{ $result['passed'] ? '✅' : '❌' }} {{ $result['criteria'] }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $applications->links() }}</div>
    @endif
</section>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full shadow-xl">
        <h3 class="text-lg font-bold text-[#0b3b5a] mb-4">Reject Application</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <input type="hidden" name="status" value="rejected">
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Reason for Rejection</label>
                <textarea name="notes" rows="3" required class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="Enter reason..."></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="hideRejectForm()" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-xl text-sm">Cancel</button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-bold">Reject</button>
            </div>
        </form>
    </div>
</div>

<script>
function showRejectForm(appId) {
    document.getElementById('rejectForm').action = '/hr/applications/' + appId + '/status';
    document.getElementById('rejectModal').classList.remove('hidden');
}
function hideRejectForm() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endsection
