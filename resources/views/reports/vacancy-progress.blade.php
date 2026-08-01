@extends('layouts.app')
@section('title', 'Vacancy Progress')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Vacancy Progress Report</h1>
            <p class="text-gray-500 mt-1">Track recruitment progress for each vacancy</p>
        </div>
        <div class="flex gap-2">
            <form action="{{ route('hr.reports.export-applications') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="vacancy_id" value="{{ request('vacancy_id') }}">
                <input type="hidden" name="format" value="excel">
                <button class="border border-gray-300 text-gray-600 px-4 py-2.5 rounded-xl text-sm hover:bg-gray-50">
                    <i class="fas fa-file-excel mr-2 text-green-600"></i> Export Excel
                </button>
            </form>
            <form action="{{ route('hr.reports.export-applications') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="vacancy_id" value="{{ request('vacancy_id') }}">
                <input type="hidden" name="format" value="pdf">
                <button class="border border-gray-300 text-gray-600 px-4 py-2.5 rounded-xl text-sm hover:bg-gray-50">
                    <i class="fas fa-file-pdf mr-2 text-red-600"></i> Export PDF
                </button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Vacancy</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Status</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Positions</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Applications</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Shortlisted</th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Selected</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Progress</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vacancies as $v)
                    @php
                        $progress = $v->positions_count > 0 ? round(($v->selected_count / $v->positions_count) * 100) : 0;
                    @endphp
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-5 py-4">
                            <p class="font-semibold">{{ $v->title }}</p>
                            <p class="text-xs text-gray-400">{{ $v->vacancy_number }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $v->status=='published'?'bg-green-100 text-green-700':'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst($v->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-center font-bold">{{ $v->positions_count }}</td>
                        <td class="px-5 py-4 text-center">{{ $v->applications_count }}</td>
                        <td class="px-5 py-4 text-center">{{ $v->shortlisted_count }}</td>
                        <td class="px-5 py-4 text-center font-bold text-green-600">{{ $v->selected_count }}</td>
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-[#0a7aa8] h-2 rounded-full" style="width:{{ $progress }}%"></div>
                                </div>
                                <span class="text-xs font-bold">{{ $progress }}%</span>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
@endsection
