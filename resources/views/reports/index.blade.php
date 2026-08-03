@extends('layouts.app')
@section('title', 'Reports & Analytics')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">
            <i class="fas fa-chart-bar text-purple-600 mr-2"></i> Reports & Analytics
        </h1>
        <p class="text-gray-500 mt-1">Comprehensive recruitment reports and data insights</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-blue-500">
            <p class="text-xs text-gray-500 uppercase">Total Vacancies</p>
            <p class="text-3xl font-extrabold text-gray-900">{{ \App\Models\Vacancy::count() }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-green-500">
            <p class="text-xs text-gray-500 uppercase">Total Applications</p>
            <p class="text-3xl font-extrabold text-green-600">{{ \App\Models\Application::count() }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-yellow-500">
            <p class="text-xs text-gray-500 uppercase">Shortlisted</p>
            <p class="text-3xl font-extrabold text-yellow-600">{{ \App\Models\Application::where('status','shortlisted')->count() }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-l-4 border-l-purple-500">
            <p class="text-xs text-gray-500 uppercase">Selected/Hired</p>
            <p class="text-3xl font-extrabold text-purple-600">{{ \App\Models\Application::where('status','selected')->count() }}</p>
        </div>
    </div>

    <!-- Report Cards Grid -->
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- 1. Vacancy Progress Report -->
        <a href="{{ route('hr.reports.vacancy-progress') }}" 
            class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md hover:border-[#0a7aa8] transition group">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-200 transition">
                <i class="fas fa-briefcase text-blue-600 text-xl"></i>
            </div>
            <h3 class="font-bold text-lg text-[#0b3b5a]">Vacancy Progress</h3>
            <p class="text-sm text-gray-500 mt-1">Track filling progress for each position with application counts and status</p>
            <span class="text-[#0a7aa8] text-sm font-semibold mt-3 inline-block">View Report →</span>
        </a>

        <!-- 2. Demographics Report -->
        <a href="{{ route('hr.reports.demographics') }}" 
            class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md hover:border-[#0a7aa8] transition group">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-green-200 transition">
                <i class="fas fa-users text-green-600 text-xl"></i>
            </div>
            <h3 class="font-bold text-lg text-[#0b3b5a]">Gender & Regional Demographics</h3>
            <p class="text-sm text-gray-500 mt-1">Gender distribution and regional breakdown of all applicants</p>
            <span class="text-[#0a7aa8] text-sm font-semibold mt-3 inline-block">View Report →</span>
        </a>

        <!-- 3. Analytics Dashboard -->
        <a href="{{ '/hr/reports/analytics' }}" 
            class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md hover:border-[#0a7aa8] transition group">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-purple-200 transition">
                <i class="fas fa-chart-pie text-purple-600 text-xl"></i>
            </div>
            <h3 class="font-bold text-lg text-[#0b3b5a]">Analytics Dashboard</h3>
            <p class="text-sm text-gray-500 mt-1">Interactive charts: status, monthly trends, gender, departments</p>
            <span class="text-[#0a7aa8] text-sm font-semibold mt-3 inline-block">View Report →</span>
        </a>

        <!-- 4. Shortlist Matrix -->
        <a href="{{ route('hr.shortlist-matrix') }}" 
            class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md hover:border-[#0a7aa8] transition group">
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-yellow-200 transition">
                <i class="fas fa-table text-yellow-600 text-xl"></i>
            </div>
            <h3 class="font-bold text-lg text-[#0b3b5a]">Candidate Comparison Matrix</h3>
            <p class="text-sm text-gray-500 mt-1">Side-by-side comparison of shortlisted candidates with scores</p>
            <span class="text-[#0a7aa8] text-sm font-semibold mt-3 inline-block">View Report →</span>
        </a>

        <!-- 5. Evaluation Summary -->
        <a href="{{ route('evaluator.summary') }}" 
            class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md hover:border-[#0a7aa8] transition group">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-orange-200 transition">
                <i class="fas fa-star text-orange-600 text-xl"></i>
            </div>
            <h3 class="font-bold text-lg text-[#0b3b5a]">Evaluation Summary</h3>
            <p class="text-sm text-gray-500 mt-1">All candidate scores, pass/fail rates, and evaluator performance</p>
            <span class="text-[#0a7aa8] text-sm font-semibold mt-3 inline-block">View Report →</span>
        </a>

        <!-- 6. Export Center -->
        <a href="{{ route('hr.applications.search') }}" 
            class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md hover:border-[#0a7aa8] transition group">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-red-200 transition">
                <i class="fas fa-file-export text-red-600 text-xl"></i>
            </div>
            <h3 class="font-bold text-lg text-[#0b3b5a]">Export Center</h3>
            <p class="text-sm text-gray-500 mt-1">Export application data to Excel or PDF format for reporting</p>
            <span class="text-[#0a7aa8] text-sm font-semibold mt-3 inline-block">Export Data →</span>
        </a>
    </div>

    <!-- Export Buttons Section -->
    <div class="mt-12 bg-white rounded-2xl p-6 shadow-sm border">
        <h2 class="font-bold text-lg text-[#0b3b5a] mb-4">Quick Exports</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <form action="{{ route('hr.reports.export-applications') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="format" value="excel">
                <button type="submit" class="w-full p-4 bg-green-50 rounded-xl text-center hover:bg-green-100 transition">
                    <i class="fas fa-file-excel text-green-600 text-2xl mb-2 block"></i>
                    <span class="text-sm font-semibold">Export Excel</span>
                    <p class="text-xs text-gray-500">All Applications</p>
                </button>
            </form>
            <form action="{{ route('hr.reports.export-applications') }}" method="POST" class="inline">
                @csrf
                <input type="hidden" name="format" value="pdf">
                <button type="submit" class="w-full p-4 bg-red-50 rounded-xl text-center hover:bg-red-100 transition">
                    <i class="fas fa-file-pdf text-red-600 text-2xl mb-2 block"></i>
                    <span class="text-sm font-semibold">Export PDF</span>
                    <p class="text-xs text-gray-500">All Applications</p>
                </button>
            </form>
            <form action="{{ route('hr.reports.export-demographics') }}" method="POST">
                @csrf
                <button type="submit" class="w-full p-4 bg-purple-50 rounded-xl text-center hover:bg-purple-100 transition">
                    <i class="fas fa-chart-bar text-purple-600 text-2xl mb-2 block"></i>
                    <span class="text-sm font-semibold">Demographics</span>
                    <p class="text-xs text-gray-500">Excel Report</p>
                </button>
            </form>
            <a href="{{ '/hr/reports/analytics' }}" 
                class="w-full p-4 bg-blue-50 rounded-xl text-center hover:bg-blue-100 transition">
                <i class="fas fa-chart-line text-blue-600 text-2xl mb-2 block"></i>
                <span class="text-sm font-semibold">Analytics</span>
                <p class="text-xs text-gray-500">Charts & Graphs</p>
            </a>
        </div>
    </div>
</section>
@endsection
