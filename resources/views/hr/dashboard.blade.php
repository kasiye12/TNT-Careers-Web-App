@extends('layouts.app')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">
            @if(Auth::user()->user_type === 'admin')
                <i class="fas fa-crown text-yellow-500 mr-2"></i> Admin Control Panel
            @else
                <i class="fas fa-gauge-high text-[#0a7aa8] mr-2"></i> HR Dashboard
            @endif
        </h1>
        <p class="text-gray-500 mt-1">Welcome, {{ Auth::user()->name }}</p>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Total Vacancies</p>
            <p class="text-3xl font-extrabold text-[#0b3b5a]">{{ \App\Models\Vacancy::count() }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Applications</p>
            <p class="text-3xl font-extrabold text-[#0a7aa8]">{{ \App\Models\Application::count() }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Shortlisted</p>
            <p class="text-3xl font-extrabold text-yellow-600">{{ \App\Models\Application::where('status','shortlisted')->count() }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Selected</p>
            <p class="text-3xl font-extrabold text-green-600">{{ \App\Models\Application::where('status','selected')->count() }}</p>
        </div>
    </div>

    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border">
                <h2 class="font-bold text-lg mb-4">Quick Actions</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <a href="{{ route('hr.vacancies.create') }}" class="p-4 bg-sky-50 rounded-xl text-center hover:bg-sky-100 transition">
                        <i class="fas fa-plus text-[#0a7aa8] text-xl mb-2 block"></i>
                        <span class="text-sm font-semibold">New Job</span>
                    </a>
                    <a href="{{ route('hr.vacancies.index') }}" class="p-4 bg-gray-100 rounded-xl text-center hover:bg-gray-200 transition">
                        <i class="fas fa-list text-gray-600 text-xl mb-2 block"></i>
                        <span class="text-sm font-semibold">All Jobs</span>
                    </a>
                    <a href="{{ route('hr.applications.review') }}" class="p-4 bg-blue-50 rounded-xl text-center hover:bg-blue-100 transition relative">
                        <i class="fas fa-clipboard-check text-blue-600 text-xl mb-2 block"></i>
                        <span class="text-sm font-semibold">Review</span>
                        @php $pendingCount = \App\Models\Application::where('status','submitted')->count(); @endphp
                        @if($pendingCount > 0)
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">
                                {{ $pendingCount }}
                            </span>
                        @endif
                    </a>
                    <a href="{{ route('hr.applications.shortlisted') }}" class="p-4 bg-yellow-50 rounded-xl text-center hover:bg-yellow-100 transition relative">
                        <i class="fas fa-star text-yellow-600 text-xl mb-2 block"></i>
                        <span class="text-sm font-semibold">Shortlisted</span>
                        @php $shortlistCount = \App\Models\Application::where('status','shortlisted')->count(); @endphp
                        @if($shortlistCount > 0)
                            <span class="absolute -top-1 -right-1 bg-yellow-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center">
                                {{ $shortlistCount }}
                            </span>
                        @endif
                    </a>
                </div>
            </div>

            <!-- Recent Vacancies -->
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                <div class="p-4 border-b flex justify-between items-center">
                    <h2 class="font-bold">Recent Vacancies</h2>
                    <a href="{{ route('hr.vacancies.create') }}" class="text-xs text-[#0a7aa8] font-semibold">+ Add</a>
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-3">Ref</th>
                            <th class="text-left px-4 py-3">Title</th>
                            <th class="text-left px-4 py-3">Status</th>
                            <th class="text-center px-4 py-3">Apps</th>
                            <th class="text-center px-4 py-3">Shortlisted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Vacancy::withCount(['applications', 'applications as shortlisted_count' => function($q) { $q->where('status', 'shortlisted'); }])->latest()->take(8)->get() as $v)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3 font-mono text-xs">{{ $v->vacancy_number }}</td>
                                <td class="px-4 py-3 font-medium">{{ $v->title }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $v->status=='published'?'bg-green-100 text-green-700':'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst($v->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center font-bold">{{ $v->applications_count }}</td>
                                <td class="px-4 py-3 text-center">
                                    @if($v->shortlisted_count > 0)
                                        <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-bold">
                                            ⭐ {{ $v->shortlisted_count }}
                                        </span>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Pipeline Summary -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border">
                <h3 class="font-bold mb-4">📊 Pipeline Overview</h3>
                @foreach([
                    ['Submitted', 'submitted', 'blue', \App\Models\Application::where('status','submitted')->count()],
                    ['Shortlisted', 'shortlisted', 'yellow', \App\Models\Application::where('status','shortlisted')->count()],
                    ['Written Exam', 'written_exam', 'purple', \App\Models\Application::where('status','written_exam')->count()],
                    ['Interview', 'interview', 'orange', \App\Models\Application::where('status','interview')->count()],
                    ['Medical', 'medical_check', 'red', \App\Models\Application::where('status','medical_check')->count()],
                    ['Selected', 'selected', 'green', \App\Models\Application::where('status','selected')->count()],
                ] as $item)
                    <div class="flex justify-between items-center py-2 border-b last:border-0">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-{{ $item[2] }}-500"></span>
                            <span class="text-sm text-gray-600">{{ $item[0] }}</span>
                        </div>
                        <a href="{{ route('hr.applications.pipeline') }}?status={{ $item[1] }}" class="font-bold text-sm hover:text-[#0a7aa8]">
                            {{ $item[3] }}
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Quick Links -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border">
                <h3 class="font-bold mb-4">Quick Links</h3>
                <div class="space-y-1">
                    <a href="{{ route('hr.applications.review') }}" class="flex items-center justify-between p-2 hover:bg-sky-50 rounded-lg text-sm">
                        <span><i class="fas fa-clipboard-check w-5 mr-2 text-blue-500"></i> Review Applications</span>
                        @if($pendingCount > 0)
                            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }} new</span>
                        @endif
                    </a>
                    <a href="{{ route('hr.applications.shortlisted') }}" class="flex items-center justify-between p-2 hover:bg-yellow-50 rounded-lg text-sm">
                        <span><i class="fas fa-star w-5 mr-2 text-yellow-500"></i> Shortlisted Candidates</span>
                        @if($shortlistCount > 0)
                            <span class="bg-yellow-100 text-yellow-700 text-xs font-bold px-2 py-0.5 rounded-full">{{ $shortlistCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('hr.applications.pipeline') }}" class="flex items-center p-2 hover:bg-sky-50 rounded-lg text-sm">
                        <i class="fas fa-sitemap w-5 mr-2 text-purple-500"></i> Pipeline Management
                    </a>
                    <a href="{{ '/hr/reports/analytics' }}" class="flex items-center p-2 hover:bg-sky-50 rounded-lg text-sm">
                        <i class="fas fa-chart-bar w-5 mr-2 text-green-500"></i> Analytics
                    </a>
                </div>
            </div>

            @if(Auth::user()->user_type === 'admin')
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-yellow-200">
                    <h3 class="font-bold mb-4 flex items-center gap-2">
                        <i class="fas fa-crown text-yellow-500"></i> Admin Panel
                    </h3>
                    <div class="space-y-1">
                        <a href="{{ route('admin.users.index') }}" class="flex items-center p-2 hover:bg-sky-50 rounded-lg text-sm">
                            <i class="fas fa-users w-5 mr-2 text-gray-400"></i> User Management
                        </a>
                        <a href="{{ route('admin.settings') }}" class="flex items-center p-2 hover:bg-sky-50 rounded-lg text-sm">
                            <i class="fas fa-cog w-5 mr-2 text-gray-400"></i> System Settings
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>
@endsection
