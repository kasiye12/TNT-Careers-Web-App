@extends('layouts.app')
@section('title', 'Pipeline Summary')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">
            <i class="fas fa-sitemap text-purple-600 mr-2"></i> Pipeline Summary Report
        </h1>
        <p class="text-gray-500 mt-1">Complete overview of all applications by stage</p>
    </div>

    @php
        $stages = [
            ['Submitted', 'submitted', 'blue'],
            ['Verified', 'document_verified', 'green'],
            ['Shortlisted', 'shortlisted', 'yellow'],
            ['Written Exam', 'written_exam', 'purple'],
            ['Interview', 'interview', 'orange'],
            ['Medical Check', 'medical_check', 'red'],
            ['Selected', 'selected', 'green'],
            ['Rejected', 'rejected', 'gray'],
        ];
        $totalApps = \App\Models\Application::count();
    @endphp

    <!-- Stage Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @foreach($stages as $stage)
            @php $count = \App\Models\Application::where('status', $stage[1])->count(); @endphp
            <div class="bg-white rounded-2xl p-5 shadow-sm border text-center">
                <div class="w-8 h-8 bg-{{ $stage[2] }}-100 rounded-lg flex items-center justify-center mx-auto mb-2">
                    <span class="text-xs font-bold text-{{ $stage[2] }}-600">{{ $count }}</span>
                </div>
                <p class="text-xs text-gray-500">{{ $stage[0] }}</p>
            </div>
        @endforeach
    </div>

    <!-- Conversion Funnel -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border mb-8">
        <h3 class="font-bold text-lg mb-6">Recruitment Funnel</h3>
        @foreach($stages as $stage)
            @php 
                $count = \App\Models\Application::where('status', $stage[1])->count();
                $pct = $totalApps > 0 ? round(($count/$totalApps)*100, 1) : 0;
            @endphp
            <div class="mb-3">
                <div class="flex justify-between text-sm mb-1">
                    <span class="font-medium">{{ $stage[0] }}</span>
                    <span class="text-gray-500">{{ $count }} ({{ $pct }}%)</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="h-4 rounded-full bg-{{ $stage[2] }}-500" style="width:{{ max($pct, 2) }}%"></div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Vacancy-wise Pipeline -->
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="p-5 border-b bg-gray-50">
            <h3 class="font-bold text-lg">Pipeline by Vacancy</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-5 py-3">Vacancy</th>
                        <th class="text-center px-4 py-3">Submitted</th>
                        <th class="text-center px-4 py-3">Shortlisted</th>
                        <th class="text-center px-4 py-3">Interview</th>
                        <th class="text-center px-4 py-3">Selected</th>
                        <th class="text-center px-4 py-3">Rejected</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach(\App\Models\Vacancy::withCount(['applications', 'applications as submitted_count' => fn($q) => $q->where('status','submitted'), 'applications as shortlisted_count' => fn($q) => $q->where('status','shortlisted'), 'applications as interview_count' => fn($q) => $q->whereIn('status',['interview','written_exam']), 'applications as selected_count' => fn($q) => $q->where('status','selected'), 'applications as rejected_count' => fn($q) => $q->where('status','rejected')])->get() as $v)
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4 font-semibold">{{ $v->title }}</td>
                            <td class="px-4 py-4 text-center">{{ $v->submitted_count }}</td>
                            <td class="px-4 py-4 text-center">{{ $v->shortlisted_count }}</td>
                            <td class="px-4 py-4 text-center">{{ $v->interview_count }}</td>
                            <td class="px-4 py-4 text-center font-bold text-green-600">{{ $v->selected_count }}</td>
                            <td class="px-4 py-4 text-center text-red-500">{{ $v->rejected_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
