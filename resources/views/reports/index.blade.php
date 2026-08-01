@extends('layouts.app')
@section('title', 'Reports')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-extrabold text-[#0b3b5a] mb-8">Reports & Analytics</h1>

    <div class="grid md:grid-cols-3 gap-6">
        <a href="{{ route('hr.reports.analytics') }}" class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md hover:border-[#0a7aa8] transition group">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-200 transition">
                <i class="fas fa-chart-pie text-purple-600 text-xl"></i>
            </div>
            <h3 class="font-bold text-lg text-[#0b3b5a]">Analytics Dashboard</h3>
            <p class="text-sm text-gray-500 mt-1">Charts, trends, and recruitment metrics</p>
        </a>

        <a href="{{ route('hr.reports.vacancy-progress') }}" class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md hover:border-[#0a7aa8] transition group">
            <div class="w-12 h-12 bg-sky-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-sky-200 transition">
                <i class="fas fa-briefcase text-[#0a7aa8] text-xl"></i>
            </div>
            <h3 class="font-bold text-lg text-[#0b3b5a]">Vacancy Progress</h3>
            <p class="text-sm text-gray-500 mt-1">Track filling progress for each position</p>
        </a>

        <a href="{{ route('hr.reports.demographics') }}" class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md hover:border-[#0a7aa8] transition group">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-green-200 transition">
                <i class="fas fa-users text-green-600 text-xl"></i>
            </div>
            <h3 class="font-bold text-lg text-[#0b3b5a]">Demographics</h3>
            <p class="text-sm text-gray-500 mt-1">Gender & regional distribution</p>
        </a>

        <a href="{{ route('hr.shortlist-matrix') }}" class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md hover:border-[#0a7aa8] transition group">
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-yellow-200 transition">
                <i class="fas fa-table text-yellow-600 text-xl"></i>
            </div>
            <h3 class="font-bold text-lg text-[#0b3b5a]">Shortlist Matrix</h3>
            <p class="text-sm text-gray-500 mt-1">Compare candidates side by side</p>
        </a>

        <a href="{{ route('hr.applications.search') }}" class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md hover:border-[#0a7aa8] transition group">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-red-200 transition">
                <i class="fas fa-search text-red-600 text-xl"></i>
            </div>
            <h3 class="font-bold text-lg text-[#0b3b5a]">Search Applications</h3>
            <p class="text-sm text-gray-500 mt-1">Advanced search & filter</p>
        </a>
    </div>
</section>
@endsection
