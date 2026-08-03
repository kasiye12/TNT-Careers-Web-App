@extends('layouts.app')
@section('title', 'Search Applications')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-extrabold text-[#0b3b5a] mb-6">Advanced Search</h1>
    
    <div class="bg-white rounded-2xl p-6 shadow-sm border mb-8">
        <form action="{{ route('hr.applications.search') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-xs font-semibold mb-1">Vacancy</label>
                <select name="vacancy_id" class="search-input w-full px-3 py-2 rounded-lg text-sm">
                    <option value="">All</option>
                    @foreach($vacancies as $id => $title)
                        <option value="{{ $id }}" {{ request('vacancy_id') == $id ? 'selected' : '' }}>{{ $title }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Status</label>
                <select name="status" class="search-input w-full px-3 py-2 rounded-lg text-sm">
                    <option value="">All</option>
                    <option value="submitted" {{ request('status')=='submitted'?'selected':'' }}>Submitted</option>
                    <option value="shortlisted" {{ request('status')=='shortlisted'?'selected':'' }}>Shortlisted</option>
                    <option value="selected" {{ request('status')=='selected'?'selected':'' }}>Selected</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold mb-1">Min Experience</label>
                <input type="number" name="min_experience" value="{{ request('min_experience') }}" class="search-input w-full px-3 py-2 rounded-lg text-sm" placeholder="Years">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn-solid-sky flex-1 py-2 text-sm rounded-lg">Search</button>
                <a href="{{ route('hr.applications.search') }}" class="border border-gray-300 text-gray-600 px-4 py-2 text-sm rounded-lg hover:bg-gray-50">Clear</a>
            </div>
        </form>
    </div>

    @if(isset($applications) && $applications->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-6 py-3">Applicant</th>
                        <th class="text-left px-6 py-3">Position</th>
                        <th class="text-left px-6 py-3">Status</th>
                        <th class="text-left px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $app)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium">{{ $app->applicant->full_name_en ?? 'N/A' ?? 'N/A' }}</td>
                            <td class="px-6 py-4">{{ $app->vacancy->title ?? 'N/A' }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-sky-100 text-[#0a5f89]">
                                    {{ ucwords(str_replace('_',' ',$app->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4"><a href="{{ route('applicant.applications.show', $app) }}" class="text-[#0a7aa8] text-xs">View</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $applications->appends(request()->query())->links() }}</div>
    @endif
</section>
@endsection
