@extends('layouts.app')
@section('title', 'Shortlist Matrix')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Candidate Comparison Matrix</h1>
        <form action="{{ route('hr.shortlist-matrix') }}" method="GET" class="flex gap-3">
            <select name="vacancy_id" required class="search-input px-4 py-3 rounded-xl text-sm">
                <option value="">Select Vacancy</option>
                @foreach(\App\Models\Vacancy::whereIn('status', ['published', 'closed'])->get() as $v)
                    <option value="{{ $v->id }}" {{ request('vacancy_id') == $v->id ? 'selected' : '' }}>{{ $v->title }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-solid-sky px-6 py-3 rounded-xl text-sm font-bold">Compare</button>
        </form>
    </div>

    @if(isset($applications) && $applications->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-sm border overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-[#0b3b5a] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">Rank</th>
                        <th class="px-4 py-3 text-left">Candidate</th>
                        <th class="px-4 py-3">Experience</th>
                        <th class="px-4 py-3">Academic (30%)</th>
                        <th class="px-4 py-3">Written (40%)</th>
                        <th class="px-4 py-3">Interview (30%)</th>
                        <th class="px-4 py-3">Total Score</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $rank = 1; @endphp
                    @foreach($applications as $app)
                        <tr class="border-b hover:bg-gray-50 {{ $rank === 1 ? 'bg-green-50' : '' }}">
                            <td class="px-4 py-3 font-bold">#{{ $rank }}</td>
                            <td class="px-4 py-3 font-medium">{{ $app->applicant->full_name_en ?? 'N/A' }}</td>
                            <td class="px-4 py-3 text-center">{{ $app->applicant->total_years_exp }} yrs</td>
                            <td class="px-4 py-3 text-center">{{ number_format($app->academic_score, 1) }}%</td>
                            <td class="px-4 py-3 text-center">{{ number_format($app->written_score, 1) }}%</td>
                            <td class="px-4 py-3 text-center">{{ number_format($app->interview_score, 1) }}%</td>
                            <td class="px-4 py-3 text-center font-extrabold {{ $app->weighted_total >= 70 ? 'text-green-600' : 'text-yellow-600' }}">
                                {{ number_format($app->weighted_total, 1) }}%
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('hr.applications.scorecard', $app) }}" class="text-[#0a7aa8] text-xs font-semibold">Details</a>
                            </td>
                        </tr>
                        @php $rank++; @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</section>
@endsection
