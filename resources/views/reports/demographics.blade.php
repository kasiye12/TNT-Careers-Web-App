@extends('layouts.app')

@section('title', 'Demographics Report')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold">Gender & Regional Demographics</h2>
                    <form action="{{ route('hr.reports.export-demographics') }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            Export Excel
                        </button>
                    </form>
                </div>

                <!-- Gender Distribution -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold mb-4">Overall Gender Distribution</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 rounded-lg p-6">
                            <canvas id="genderChart"></canvas>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <table class="min-w-full">
                                <thead>
                                    <tr>
                                        <th class="text-left">Gender</th>
                                        <th class="text-right">Count</th>
                                        <th class="text-right">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $total = $genderStats->sum('count'); @endphp
                                    @foreach($genderStats as $stat)
                                        <tr>
                                            <td class="py-2">{{ ucfirst($stat->gender) }}</td>
                                            <td class="text-right">{{ $stat->count }}</td>
                                            <td class="text-right">{{ $total > 0 ? round(($stat->count / $total) * 100, 1) : 0 }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Regional Distribution -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Regional Distribution</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Region</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Male</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Female</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($regionalGenderStats as $region => $genders)
                                    @php
                                        $maleCount = $genders->where('gender', 'male')->first()->count ?? 0;
                                        $femaleCount = $genders->where('gender', 'female')->first()->count ?? 0;
                                        $regionTotal = $maleCount + $femaleCount;
                                    @endphp
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ $region }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                            {{ $maleCount }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-900">
                                            {{ $femaleCount }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900">
                                            {{ $regionTotal }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('genderChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: [
                @foreach($genderStats as $stat)
                    '{{ ucfirst($stat->gender) }}',
                @endforeach
            ],
            datasets: [{
                data: [
                    @foreach($genderStats as $stat)
                        {{ $stat->count }},
                    @endforeach
                ],
                backgroundColor: ['#3B82F6', '#EC4899'],
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
@endpush
@endsection
