@extends('layouts.app')
@section('title', 'System Overview')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-extrabold text-[#0b3b5a] mb-8">
        <i class="fas fa-info-circle text-blue-500 mr-2"></i> System Overview
    </h1>

    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-4">Quick Links</h3>
            <div class="space-y-2">
                <a href="/" class="flex items-center p-2 hover:bg-gray-50 rounded-lg text-sm">
                    <i class="fas fa-home w-6 text-blue-500"></i> Homepage
                </a>
                <a href="{{ route('vacancies.public.index') }}" class="flex items-center p-2 hover:bg-gray-50 rounded-lg text-sm">
                    <i class="fas fa-briefcase w-6 text-green-500"></i> Job Listings
                </a>
                <a href="{{ route('login') }}" class="flex items-center p-2 hover:bg-gray-50 rounded-lg text-sm">
                    <i class="fas fa-sign-in-alt w-6 text-purple-500"></i> Login
                </a>
                <a href="{{ route('register') }}" class="flex items-center p-2 hover:bg-gray-50 rounded-lg text-sm">
                    <i class="fas fa-user-plus w-6 text-orange-500"></i> Register
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-4">Career Tools</h3>
            <div class="space-y-2">
                <a href="{{ route('cv.generator') }}" class="flex items-center p-2 hover:bg-gray-50 rounded-lg text-sm">
                    <i class="fas fa-file-pdf w-6 text-red-500"></i> CV Generator
                </a>
                <a href="{{ route('resume.builder') }}" class="flex items-center p-2 hover:bg-gray-50 rounded-lg text-sm">
                    <i class="fas fa-file-alt w-6 text-indigo-500"></i> Resume Builder
                </a>
                <a href="{{ route('salary.calculator') }}" class="flex items-center p-2 hover:bg-gray-50 rounded-lg text-sm">
                    <i class="fas fa-calculator w-6 text-green-500"></i> Salary Calculator
                </a>
                <a href="{{ route('interview.tips') }}" class="flex items-center p-2 hover:bg-gray-50 rounded-lg text-sm">
                    <i class="fas fa-lightbulb w-6 text-yellow-500"></i> Interview Tips
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-4">Admin & HR</h3>
            <div class="space-y-2">
                <a href="{{ route('hr.dashboard') }}" class="flex items-center p-2 hover:bg-gray-50 rounded-lg text-sm">
                    <i class="fas fa-gauge-high w-6 text-blue-500"></i> HR Dashboard
                </a>
                <a href="{{ route('hr.vacancies.index') }}" class="flex items-center p-2 hover:bg-gray-50 rounded-lg text-sm">
                    <i class="fas fa-list w-6 text-green-500"></i> Manage Vacancies
                </a>
                <a href="{{ route('hr.applications.review') }}" class="flex items-center p-2 hover:bg-gray-50 rounded-lg text-sm">
                    <i class="fas fa-clipboard-check w-6 text-purple-500"></i> Review Applications
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center p-2 hover:bg-gray-50 rounded-lg text-sm">
                    <i class="fas fa-users w-6 text-orange-500"></i> User Management
                </a>
            </div>
        </div>
    </div>

    <!-- System Stats -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border mb-8">
        <h3 class="font-bold text-lg mb-4">System Statistics</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            <div class="p-4 bg-gray-50 rounded-xl">
                <p class="text-3xl font-extrabold text-[#0b3b5a]">{{ \App\Models\User::count() }}</p>
                <p class="text-xs text-gray-500">Users</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl">
                <p class="text-3xl font-extrabold text-blue-600">{{ \App\Models\Vacancy::count() }}</p>
                <p class="text-xs text-gray-500">Vacancies</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl">
                <p class="text-3xl font-extrabold text-green-600">{{ \App\Models\Application::count() }}</p>
                <p class="text-xs text-gray-500">Applications</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-xl">
                <p class="text-3xl font-extrabold text-purple-600">{{ \App\Models\EvaluationScore::count() }}</p>
                <p class="text-xs text-gray-500">Evaluations</p>
            </div>
        </div>
    </div>
</section>
@endsection
