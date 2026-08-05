@extends('layouts.app')
@section('content')

@php
    $user = Auth::user();
    $userDepartment = $user->department;
    $isAdmin = $user->user_type === 'admin';
    
    // Department-filtered stats
    if ($isAdmin) {
        $totalVacancies = \App\Models\Vacancy::count();
        $totalApplications = \App\Models\Application::count();
        $shortlisted = \App\Models\Application::where('status','shortlisted')->count();
        $selected = \App\Models\Application::where('status','selected')->count();
    } else {
        $totalVacancies = \App\Models\Vacancy::where('department', 'like', '%'.$userDepartment.'%')->count();
        $totalApplications = \App\Models\Application::whereHas('vacancy', fn($q) => $q->where('department', 'like', '%'.$userDepartment.'%'))->count();
        $shortlisted = \App\Models\Application::where('status','shortlisted')->whereHas('vacancy', fn($q) => $q->where('department', 'like', '%'.$userDepartment.'%'))->count();
        $selected = \App\Models\Application::where('status','selected')->whereHas('vacancy', fn($q) => $q->where('department', 'like', '%'.$userDepartment.'%'))->count();
    }
@endphp

<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">
            @if($isAdmin)
                <i class="fas fa-crown text-yellow-500 mr-2"></i> Admin Control Panel
            @else
                <i class="fas fa-building text-[#0a7aa8] mr-2"></i> {{ $userDepartment }} Dashboard
            @endif
        </h1>
        <p class="text-gray-500 mt-1">
            Welcome, {{ $user->name }} 
            @if(!$isAdmin && $userDepartment)
                | Department: <strong class="text-[#0a7aa8]">{{ $userDepartment }}</strong>
            @endif
        </p>
        
        @if(!$isAdmin && !$userDepartment)
            <div class="mt-3 p-4 bg-red-50 border border-red-200 rounded-xl text-sm text-red-700">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>No department assigned!</strong> Contact Admin to assign your department. 
                Some features are hidden until a department is assigned.
            </div>
        @endif
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">{{ $isAdmin ? 'Total' : $userDepartment }} Vacancies</p>
            <p class="text-3xl font-extrabold text-[#0b3b5a]">{{ $totalVacancies }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Applications</p>
            <p class="text-3xl font-extrabold text-[#0a7aa8]">{{ $totalApplications }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Shortlisted</p>
            <p class="text-3xl font-extrabold text-yellow-600">{{ $shortlisted }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Selected</p>
            <p class="text-3xl font-extrabold text-green-600">{{ $selected }}</p>
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
                    <a href="{{ route('hr.applications.review') }}" class="p-4 bg-green-50 rounded-xl text-center hover:bg-green-100 transition">
                        <i class="fas fa-clipboard-check text-green-600 text-xl mb-2 block"></i>
                        <span class="text-sm font-semibold">Review</span>
                    </a>
                    <a href="{{ route('hr.applications.pipeline') }}" class="p-4 bg-purple-50 rounded-xl text-center hover:bg-purple-100 transition">
                        <i class="fas fa-sitemap text-purple-600 text-xl mb-2 block"></i>
                        <span class="text-sm font-semibold">Pipeline</span>
                    </a>
                </div>
            </div>

            <!-- Recent Vacancies - Department Filtered -->
            <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
                <div class="p-4 border-b"><h2 class="font-bold">Recent Vacancies {{ !$isAdmin ? ' - ' . $userDepartment : '' }}</h2></div>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left px-4 py-3">Ref</th>
                            <th class="text-left px-4 py-3">Title</th>
                            <th class="text-left px-4 py-3">Department</th>
                            <th class="text-left px-4 py-3">Status</th>
                            <th class="text-left px-4 py-3">Apps</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $query = \App\Models\Vacancy::latest();
                            if (!$isAdmin && $userDepartment) {
                                $query->where('department', 'like', '%'.$userDepartment.'%');
                            }
                            $recentVacancies = $query->take(8)->get();
                        @endphp
                        @foreach($recentVacancies as $v)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="px-4 py-3 font-mono text-xs">{{ $v->vacancy_number }}</td>
                                <td class="px-4 py-3 font-medium">{{ $v->title }}</td>
                                <td class="px-4 py-3 text-xs text-gray-500">{{ Str::limit($v->department, 20) }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $v->status=='published'?'bg-green-100 text-green-700':'bg-gray-100 text-gray-600' }}">
                                        {{ ucfirst($v->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-bold">{{ $v->applications_count ?? 0 }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Pipeline -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border">
                <h3 class="font-bold mb-4">Pipeline {{ !$isAdmin ? ' - ' . $userDepartment : '' }}</h3>
                @foreach([['Submitted','submitted'],['Shortlisted','shortlisted'],['Interview','interview'],['Selected','selected']] as [$l,$s])
                    @php
                        if ($isAdmin) {
                            $count = \App\Models\Application::where('status',$s)->count();
                        } else {
                            $count = \App\Models\Application::where('status',$s)
                                ->whereHas('vacancy', fn($q) => $q->where('department', 'like', '%'.$userDepartment.'%'))
                                ->count();
                        }
                    @endphp
                    <div class="flex justify-between py-2 border-b last:border-0">
                        <span class="text-sm">{{ $l }}</span>
                        <span class="font-bold">{{ $count }}</span>
                    </div>
                @endforeach
            </div>

            <!-- ADMIN ONLY SECTION -->
            @if($isAdmin)
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-yellow-200">
                    <h3 class="font-bold mb-4"><i class="fas fa-crown text-yellow-500 mr-2"></i> Admin Panel</h3>
                    <div class="space-y-1">
                        <a href="{{ route('admin.users.index') }}" class="flex items-center p-2 hover:bg-sky-50 rounded-lg text-sm">
                            <i class="fas fa-users w-5 mr-2 text-gray-400"></i> User Management
                        </a>
                        <a href="{{ route('admin.settings') }}" class="flex items-center p-2 hover:bg-sky-50 rounded-lg text-sm">
                            <i class="fas fa-cog w-5 mr-2 text-gray-400"></i> System Settings
                        </a>
                        <a href="{{ route('hr.evaluations.overview') }}" class="flex items-center p-2 hover:bg-sky-50 rounded-lg text-sm">
                            <i class="fas fa-clipboard-list w-5 mr-2 text-gray-400"></i> All Evaluations
                        </a>
                    </div>
                </div>
            @endif

            <!-- Reports -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border">
                <h3 class="font-bold mb-4">Reports</h3>
                <div class="space-y-1">
                    <a href="{{ route('hr.reports.vacancy-progress') }}" class="flex items-center p-2 hover:bg-sky-50 rounded-lg text-sm">
                        <i class="fas fa-briefcase w-5 mr-2 text-gray-400"></i> Vacancy Progress
                    </a>
                    <a href="{{ route('hr.reports.demographics') }}" class="flex items-center p-2 hover:bg-sky-50 rounded-lg text-sm">
                        <i class="fas fa-users w-5 mr-2 text-gray-400"></i> Demographics
                    </a>
                        <i class="fas fa-chart-bar w-5 mr-2 text-gray-400"></i> Analytics
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
