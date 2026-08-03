@extends('layouts.app')
@section('title', 'Analytics Dashboard')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">
            <i class="fas fa-chart-pie text-purple-600 mr-2"></i> Analytics Dashboard
        </h1>
        <p class="text-gray-500 mt-1">Recruitment metrics and insights</p>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Total Vacancies</p>
            <p class="text-3xl font-extrabold text-[#0b3b5a] mt-1">{{ \App\Models\Vacancy::count() }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Applications</p>
            <p class="text-3xl font-extrabold text-[#0a7aa8] mt-1">{{ \App\Models\Application::count() }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Selected</p>
            <p class="text-3xl font-extrabold text-green-600 mt-1">{{ \App\Models\Application::where('status','selected')->count() }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase">Conversion Rate</p>
            @php
                $total = \App\Models\Application::count();
                $hired = \App\Models\Application::where('status','selected')->count();
                $rate = $total > 0 ? round(($hired/$total)*100, 1) : 0;
            @endphp
            <p class="text-3xl font-extrabold text-purple-600 mt-1">{{ $rate }}%</p>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <!-- Applications by Status -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-4">Applications by Status</h3>
            <canvas id="statusChart" height="200"></canvas>
        </div>

        <!-- Applications by Month -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-4">Monthly Applications (Last 6 Months)</h3>
            <canvas id="monthlyChart" height="200"></canvas>
        </div>

        <!-- Gender Distribution -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-4">Gender Distribution</h3>
            <canvas id="genderChart" height="200"></canvas>
        </div>

        <!-- Applications by Department -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-4">Top Departments</h3>
            <canvas id="deptChart" height="200"></canvas>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="p-5 border-b">
            <h2 class="font-bold text-lg">Recent Application Activity</h2>
        </div>
        <table class="w-full text-sm">
            <thead class="bg-gray-50">
                <tr>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Applicant</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Position</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Status</th>
                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach(\App\Models\Application::with(['vacancy', 'applicant'])->latest()->take(10)->get() as $app)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-4 font-semibold">{{ $app->applicant->full_name_en ?? 'N/A' ?? 'N/A' }}</td>
                        <td class="px-5 py-4 text-gray-600">{{ $app->vacancy->title ?? 'N/A' ?? 'N/A' }}</td>
                        <td class="px-5 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                @if($app->status == 'selected') bg-green-100 text-green-700
                                @elseif($app->status == 'rejected') bg-red-100 text-red-600
                                @else bg-blue-100 text-blue-700 @endif">
                                {{ ucwords(str_replace('_', ' ', $app->status)) }}
                            </span>
                        </td>
                        <td class="px-5 py-4 text-gray-500 text-xs">{{ $app->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>

<script>
// Status Chart
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Submitted', 'Shortlisted', 'Interview', 'Selected', 'Rejected'],
        datasets: [{
            data: [
                {{ \App\Models\Application::where('status','submitted')->count() }},
                {{ \App\Models\Application::where('status','shortlisted')->count() }},
                {{ \App\Models\Application::whereIn('status',['written_exam','interview'])->count() }},
                {{ \App\Models\Application::where('status','selected')->count() }},
                {{ \App\Models\Application::where('status','rejected')->count() }}
            ],
            backgroundColor: ['#3b82f6', '#f59e0b', '#8b5cf6', '#10b981', '#ef4444']
        }]
    }
});

// Monthly Chart
new Chart(document.getElementById('monthlyChart'), {
    type: 'line',
    data: {
        labels: [
            @for($i=5; $i>=0; $i--) '{{ now()->subMonths($i)->format('M Y') }}', @endfor
        ],
        datasets: [{
            label: 'Applications',
            data: [
                @for($i=5; $i>=0; $i--)
                    {{ \App\Models\Application::whereMonth('created_at', now()->subMonths($i)->month)->whereYear('created_at', now()->subMonths($i)->year)->count() }},
                @endfor
            ],
            borderColor: '#0a7aa8',
            backgroundColor: 'rgba(10,122,168,0.1)',
            fill: true,
            tension: 0.4
        }]
    }
});

// Gender Chart
new Chart(document.getElementById('genderChart'), {
    type: 'pie',
    data: {
        labels: ['Male', 'Female'],
        datasets: [{
            data: [
                {{ \App\Models\Applicant::where('gender','male')->count() }},
                {{ \App\Models\Applicant::where('gender','female')->count() }}
            ],
            backgroundColor: ['#3b82f6', '#ec4899']
        }]
    }
});

// Department Chart
new Chart(document.getElementById('deptChart'), {
    type: 'bar',
    data: {
        labels: ['Engineering', 'HSE', 'Finance', 'Logistics', 'Management'],
        datasets: [{
            label: 'Applications',
            data: [
                {{ \App\Models\Application::whereHas('vacancy', fn($q) => $q->where('department','like','%Engineering%'))->count() }},
                {{ \App\Models\Application::whereHas('vacancy', fn($q) => $q->where('department','like','%HSE%'))->count() }},
                {{ \App\Models\Application::whereHas('vacancy', fn($q) => $q->where('department','like','%Finance%'))->count() }},
                {{ \App\Models\Application::whereHas('vacancy', fn($q) => $q->where('department','like','%Logistics%'))->count() }},
                {{ \App\Models\Application::whereHas('vacancy', fn($q) => $q->where('department','like','%Management%'))->count() }}
            ],
            backgroundColor: '#0a7aa8'
        }]
    }
});
</script>
@endsection
