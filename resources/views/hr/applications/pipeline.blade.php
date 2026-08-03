@extends('layouts.app')
@section('title', 'Application Pipeline')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">
            <i class="fas fa-sitemap text-purple-600 mr-2"></i> Application Pipeline
        </h1>
        <p class="text-gray-500 mt-1">Manage candidates through recruitment stages</p>
    </div>

    <!-- Pipeline Flow -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border mb-8">
        <div class="flex flex-wrap items-center justify-center gap-2 text-xs font-semibold">
            <span class="px-3 py-2 bg-blue-100 text-blue-700 rounded-lg">📝 Submitted</span>
            <i class="fas fa-arrow-right text-gray-400"></i>
            <span class="px-3 py-2 bg-green-100 text-green-700 rounded-lg">✅ Verified</span>
            <i class="fas fa-arrow-right text-gray-400"></i>
            <span class="px-3 py-2 bg-yellow-100 text-yellow-700 rounded-lg">⭐ Shortlisted</span>
            <i class="fas fa-arrow-right text-gray-400"></i>
            <span class="px-3 py-2 bg-purple-100 text-purple-700 rounded-lg">✍️ Written Exam</span>
            <i class="fas fa-arrow-right text-gray-400"></i>
            <span class="px-3 py-2 bg-orange-100 text-orange-700 rounded-lg">🎤 Interview</span>
            <i class="fas fa-arrow-right text-gray-400"></i>
            <span class="px-3 py-2 bg-red-100 text-red-700 rounded-lg">🏥 Medical <span class="text-[10px]">(Optional)</span></span>
            <i class="fas fa-arrow-right text-gray-400"></i>
            <span class="px-3 py-2 bg-green-200 text-green-800 rounded-lg">🎉 Selected</span>
        </div>
    </div>

    <!-- Stage Filter Tabs -->
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="p-4 border-b bg-gray-50 flex gap-2 flex-wrap">
            <a href="?status=written_exam" 
                class="px-4 py-2 rounded-lg text-sm font-semibold transition
                {{ request('status') == 'written_exam' || !request('status') ? 'bg-purple-100 text-purple-700' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                ✍️ Written Exam
            </a>
            <a href="?status=interview" 
                class="px-4 py-2 rounded-lg text-sm font-semibold transition
                {{ request('status') == 'interview' ? 'bg-orange-100 text-orange-700' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                🎤 Interview
            </a>
            <a href="?status=medical_check" 
                class="px-4 py-2 rounded-lg text-sm font-semibold transition
                {{ request('status') == 'medical_check' ? 'bg-red-100 text-red-700' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                🏥 Medical <span class="text-[10px] text-gray-400">(Optional)</span>
            </a>
            <a href="?status=selected" 
                class="px-4 py-2 rounded-lg text-sm font-semibold transition
                {{ request('status') == 'selected' ? 'bg-green-100 text-green-700' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                🎉 Selected
            </a>
            <a href="?status=rejected" 
                class="px-4 py-2 rounded-lg text-sm font-semibold transition
                {{ request('status') == 'rejected' ? 'bg-red-100 text-red-600' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                ❌ Rejected
            </a>
        </div>

        @php
            $status = request('status', 'written_exam');
            $applications = \App\Models\Application::with(['vacancy', 'applicant.user'])
                ->where('status', $status)
                ->latest()
                ->paginate(20);
        @endphp

        @if($applications->isEmpty())
            <div class="p-12 text-center text-gray-400">
                <i class="fas fa-inbox text-5xl mb-3 block"></i>
                <p class="font-semibold">No applications in this stage</p>
                <p class="text-sm">Move candidates here from previous stages</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Candidate</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500">Position</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500">Experience</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500">Status</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($applications as $app)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 bg-sky-100 rounded-full flex items-center justify-center text-xs font-bold text-[#0a7aa8]">
                                            {{ substr($app->applicant->full_name_en ?? 'N/A' ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold">{{ $app->applicant->full_name_en ?? 'N/A' ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-400">{{ $app->applicant->user->email ?? '' ?? '' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="font-medium">{{ $app->vacancy->title ?? 'N/A' ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-400">{{ $app->vacancy->vacancy_number ?? '' }}</p>
                                </td>
                                <td class="px-4 py-4 text-center">{{ $app->applicant->total_years_exp ?? 0 }} yrs</td>
                                <td class="px-4 py-4 text-center">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold
                                        @if($app->status == 'written_exam') bg-purple-100 text-purple-700
                                        @elseif($app->status == 'interview') bg-orange-100 text-orange-700
                                        @elseif($app->status == 'medical_check') bg-red-100 text-red-700
                                        @elseif($app->status == 'selected') bg-green-100 text-green-700
                                        @else bg-red-100 text-red-600 @endif">
                                        {{ ucwords(str_replace('_', ' ', $app->status)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($app->status == 'written_exam')
                                            <form action="{{ route('hr.applications.update-status', $app) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="interview">
                                                <button class="px-3 py-1.5 bg-orange-100 text-orange-700 rounded-lg text-xs font-bold hover:bg-orange-200">
                                                    → Interview
                                                </button>
                                            </form>
                                        @elseif($app->status == 'interview')
                                            <form action="{{ route('hr.applications.update-status', $app) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="medical_check">
                                                <button class="px-3 py-1.5 bg-red-50 text-red-600 rounded-lg text-xs font-bold hover:bg-red-100">
                                                    → Medical
                                                </button>
                                            </form>
                                            <form action="{{ route('hr.applications.update-status', $app) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="selected">
                                                <button class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-xs font-bold hover:bg-green-200">
                                                    ✅ Select Directly
                                                </button>
                                            </form>
                                        @elseif($app->status == 'medical_check')
                                            <form action="{{ route('hr.applications.update-status', $app) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="selected">
                                                <button class="px-3 py-1.5 bg-green-100 text-green-700 rounded-lg text-xs font-bold hover:bg-green-200">
                                                    ✅ Select
                                                </button>
                                            </form>
                                        @elseif($app->status == 'selected')
                                            <a href="{{ route('hr.offer-letters.create', $app) }}" 
                                                class="px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold hover:bg-blue-200">
                                                📄 Offer Letter
                                            </a>
                                        @endif

                                        @if(!in_array($app->status, ['selected', 'rejected']))
                                            <form action="{{ route('hr.applications.update-status', $app) }}" method="POST"
                                                onsubmit="return confirm('Reject this application?')">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button class="px-3 py-1.5 bg-red-50 text-red-500 rounded-lg text-xs font-bold hover:bg-red-100">
                                                    ❌ Reject
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('applicant.applications.show', $app) }}" 
                                            class="p-1.5 text-gray-400 hover:text-[#0a7aa8] rounded-lg" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($applications->hasPages())
                <div class="px-5 py-4 border-t">{{ $applications->appends(request()->query())->links() }}</div>
            @endif
        @endif
    </div>
</section>
@endsection
