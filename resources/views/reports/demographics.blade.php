@extends('layouts.app')
@section('title', 'Demographics Report')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0b3b5a]">
                <i class="fas fa-users text-green-600 mr-2"></i> Gender & Regional Demographics
            </h1>
            <p class="text-gray-500 mt-1">Applicant diversity and regional distribution</p>
        </div>
        <form action="{{ route('hr.reports.export-demographics') }}" method="POST">
            @csrf
            <button type="submit" class="border border-gray-300 text-gray-600 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50">
                <i class="fas fa-file-excel mr-2 text-green-600"></i> Export Excel
            </button>
        </form>
    </div>

    @php
        $genderStats = \App\Models\Applicant::selectRaw('gender, COUNT(*) as count')->groupBy('gender')->get();
        $totalApplicants = \App\Models\Applicant::count();
        $regionalStats = \App\Models\Applicant::selectRaw('region, gender, COUNT(*) as count')
            ->groupBy('region', 'gender')->get()->groupBy('region');
    @endphp

    <!-- Gender Distribution -->
    <div class="grid md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-4">Gender Distribution</h3>
            <div class="space-y-4">
                @foreach($genderStats as $stat)
                    @php $pct = $totalApplicants > 0 ? round(($stat->count/$totalApplicants)*100, 1) : 0; @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="font-semibold">{{ ucfirst($stat->gender) }}</span>
                            <span>{{ $stat->count }} ({{ $pct }}%)</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-3">
                            <div class="h-3 rounded-full {{ $stat->gender == 'male' ? 'bg-blue-500' : 'bg-pink-500' }}" style="width:{{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
                <div class="pt-4 border-t text-center">
                    <p class="text-2xl font-extrabold text-[#0b3b5a]">{{ $totalApplicants }}</p>
                    <p class="text-xs text-gray-500">Total Applicants</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-4">Gender Ratio</h3>
            <div class="flex items-center justify-center h-48">
                @php
                    $maleCount = $genderStats->where('gender','male')->first()->count ?? 0;
                    $femaleCount = $genderStats->where('gender','female')->first()->count ?? 0;
                @endphp
                <div class="text-center">
                    <div class="flex items-end gap-4 justify-center">
                        <div>
                            <div class="bg-blue-500 w-20 rounded-t-xl" style="height:{{ $totalApplicants > 0 ? ($maleCount/$totalApplicants)*150 : 0 }}px"></div>
                            <p class="text-sm font-bold mt-2">Male</p>
                            <p class="text-xs text-gray-500">{{ $maleCount }}</p>
                        </div>
                        <div>
                            <div class="bg-pink-500 w-20 rounded-t-xl" style="height:{{ $totalApplicants > 0 ? ($femaleCount/$totalApplicants)*150 : 0 }}px"></div>
                            <p class="text-sm font-bold mt-2">Female</p>
                            <p class="text-xs text-gray-500">{{ $femaleCount }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Regional Distribution -->
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="p-5 border-b bg-gray-50">
            <h3 class="font-bold text-lg">Regional Distribution</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Region</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Male</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Female</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">Total</th>
                        <th class="text-center px-5 py-3 text-xs font-semibold text-gray-500">% of Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($regionalStats as $region => $genders)
                        @php
                            $m = $genders->where('gender','male')->first()->count ?? 0;
                            $f = $genders->where('gender','female')->first()->count ?? 0;
                            $total = $m + $f;
                            $pct = $totalApplicants > 0 ? round(($total/$totalApplicants)*100, 1) : 0;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-5 py-4 font-semibold">{{ $region }}</td>
                            <td class="px-5 py-4 text-center">{{ $m }}</td>
                            <td class="px-5 py-4 text-center">{{ $f }}</td>
                            <td class="px-5 py-4 text-center font-bold">{{ $total }}</td>
                            <td class="px-5 py-4 text-center">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 bg-gray-200 rounded-full h-2">
                                        <div class="bg-[#0a7aa8] h-2 rounded-full" style="width:{{ $pct }}%"></div>
                                    </div>
                                    <span class="text-xs">{{ $pct }}%</span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
