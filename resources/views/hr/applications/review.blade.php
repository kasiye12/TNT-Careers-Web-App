@extends('layouts.app')
@section('title', 'Review Applications')
@section('content')

@php
    $user = Auth::user();
    $userType = $user->user_type;
    $userDepartment = $user->department;
    $isAdmin = $userType === 'admin';
    $isHR = $userType === 'hr_manager';
@endphp

<section class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0b3b5a]">📝 Review Applications</h1>
            <p class="text-gray-500 mt-1">
                @if($isAdmin)
                    Viewing ALL applications from all departments
                @elseif($isHR)
                    Viewing ALL applications | You can manage {{ $userDepartment ?? 'your' }} department
                @else
                    Viewing {{ $userDepartment ?? 'your' }} department applications
                @endif
            </p>
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
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm">{{ session('error') }}</div>
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
                @php
                    $vacancyDept = $app->vacancy->department ?? '';
                    $isMyDept = $userDepartment && (stripos($vacancyDept, $userDepartment) !== false || stripos($userDepartment, $vacancyDept) !== false);
                    $canManage = $isAdmin || ($isHR && $isMyDept);
                @endphp
                <div class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md transition">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start gap-4 flex-1">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <span class="text-blue-700 font-bold text-lg">{{ substr($app->applicant->full_name_en ?? '?', 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="font-bold text-lg text-[#0b3b5a]">{{ $app->applicant->full_name_en ?? 'N/A' }}</h3>
                                    @if($isMyDept)
                                        <span class="text-[10px] bg-indigo-100 text-indigo-700 px-1.5 py-0.5 rounded">Your Dept</span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500">{{ $app->applicant->user->email ?? '' }} | {{ $app->applicant->user->phone ?? '' }}</p>
                                <div class="mt-2 p-3 bg-gray-50 rounded-xl">
                                    <p class="font-semibold text-sm">{{ $app->vacancy->title ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-400">{{ $app->vacancy->vacancy_number ?? '' }} | {{ $vacancyDept }}</p>
                                </div>
                                <div class="flex flex-wrap gap-2 mt-2 text-xs text-gray-500">
                                    <span>{{ $app->applicant->total_years_exp ?? 0 }} yrs exp</span>
                                    <span>{{ $app->applicant->educationHistories->first()->qualification_label ?? 'N/A' }}</span>
                                    <span>Applied {{ $app->created_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        @if($canManage)
                        <div class="flex flex-col gap-2 flex-shrink-0 min-w-[170px]">
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wide text-center">Actions</span>
                            <form action="{{ route('hr.applications.update-status', $app) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="document_verified">
                                <button class="w-full px-4 py-2.5 bg-green-600 text-white rounded-xl text-sm font-bold hover:bg-green-700 transition">
                                    ✅ Verify Documents
                                </button>
                            </form>
                            <form action="{{ route('hr.applications.update-status', $app) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="shortlisted">
                                <button class="w-full px-4 py-2.5 bg-yellow-500 text-white rounded-xl text-sm font-bold hover:bg-yellow-600 transition">
                                    ⭐ Shortlist
                                </button>
                            </form>
                            <button onclick="showRejectModal('{{ $app->id }}')" 
                                class="w-full px-4 py-2.5 bg-red-50 text-red-600 rounded-xl text-sm font-bold hover:bg-red-100 transition border border-red-200">
                                ❌ Reject
                            </button>
                            <a href="{{ route('applicant.applications.show', $app) }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 transition text-center">
                                👁️ View Details
                            </a>
                        </div>
                        @else
                        <div class="flex flex-col gap-2 flex-shrink-0">
                            <span class="text-xs text-gray-400 text-center">No actions available</span>
                            <a href="{{ route('applicant.applications.show', $app) }}" 
                                class="px-4 py-2.5 border border-gray-300 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 transition text-center">
                                👁️ View Details
                            </a>
                        </div>
                        @endif
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
        <h3 class="text-lg font-bold mb-4">❌ Reject Application</h3>
        <form id="rejectForm" method="POST">
            @csrf
            <input type="hidden" name="status" value="rejected">
            <textarea name="notes" rows="2" class="search-input w-full px-4 py-3 rounded-xl text-sm mb-4" placeholder="Reason..."></textarea>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-xl text-sm">Cancel</button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-bold">Confirm</button>
            </div>
        </form>
    </div>
</div>
<script>
function showRejectModal(appId) {
    document.getElementById('rejectForm').action = '/hr/applications/' + appId + '/status';
    document.getElementById('rejectModal').classList.remove('hidden');
}
</script>
@endsection
