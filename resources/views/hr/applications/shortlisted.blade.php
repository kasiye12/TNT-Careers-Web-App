@extends('layouts.app')
@section('title', 'Shortlisted Candidates')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0b3b5a]">⭐ Shortlisted Candidates</h1>
            <p class="text-gray-500 mt-1">Top candidates ready for next steps</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('hr.applications.pipeline') }}" class="border border-gray-300 text-gray-600 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50">
                <i class="fas fa-sitemap mr-2"></i> Pipeline View
            </a>
        </div>
    </div>

    @if($applications->isEmpty())
        <div class="bg-white rounded-2xl p-12 text-center shadow-sm border">
            <div class="text-6xl mb-4">⭐</div>
            <h3 class="text-xl font-bold text-[#0b3b5a] mb-2">No Shortlisted Candidates</h3>
            <p class="text-gray-500">Shortlist candidates from the review page.</p>
            <a href="{{ route('hr.applications.review') }}" class="btn-solid-sky mt-4 inline-block text-sm px-6 py-2.5 rounded-xl">Go to Review</a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($applications as $app)
                <div class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md transition">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div class="flex items-start gap-4 flex-1">
                            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <span class="text-yellow-700 font-bold text-lg">{{ substr($app->applicant->full_name_en ?? 'N/A' ?? '?', 0, 1) }}</span>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-[#0b3b5a]">{{ $app->applicant->full_name_en ?? 'N/A' ?? 'N/A' }}</h3>
                                <p class="text-sm text-gray-500">{{ $app->applicant->user->email ?? '' ?? '' }}</p>
                                <div class="flex flex-wrap gap-2 mt-2 text-sm text-gray-600">
                                    <span><i class="fas fa-briefcase mr-1"></i> {{ $app->vacancy->title ?? 'N/A' }}</span>
                                    <span><i class="fas fa-clock mr-1"></i> {{ $app->applicant->total_years_exp ?? 0 }} yrs</span>
                                </div>
                            </div>
                        </div>

                        <!-- ACTIONS -->
                        <div class="flex flex-col gap-2 flex-shrink-0 min-w-[180px]">
                            <span class="text-xs text-gray-400 font-semibold uppercase tracking-wide text-center">Next Steps</span>
                            
                            <!-- Move to Written Exam -->
                            <form action="{{ route('hr.applications.update-status', $app) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="written_exam">
                                <button class="w-full px-4 py-2.5 bg-purple-600 text-white rounded-xl text-sm font-bold hover:bg-purple-700 transition">
                                    ✍️ Written Exam
                                </button>
                            </form>

                            <!-- Skip to Interview -->
                            <form action="{{ route('hr.applications.update-status', $app) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="interview">
                                <button class="w-full px-4 py-2.5 bg-orange-600 text-white rounded-xl text-sm font-bold hover:bg-orange-700 transition">
                                    🎤 Direct to Interview
                                </button>
                            </form>

                            <!-- Reject -->
                            <form action="{{ route('hr.applications.update-status', $app) }}" method="POST"
                                onsubmit="return confirm('Reject this candidate?')">
                                @csrf
                                <input type="hidden" name="status" value="rejected">
                                <button class="w-full px-4 py-2.5 bg-red-50 text-red-600 rounded-xl text-sm font-bold hover:bg-red-100 border border-red-200">
                                    ❌ Reject
                                </button>
                            </form>

                            <a href="{{ route('applicant.applications.show', $app) }}" 
                                class="w-full px-4 py-2.5 border border-gray-300 text-gray-600 rounded-xl text-sm font-semibold hover:bg-gray-50 text-center">
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
@endsection
