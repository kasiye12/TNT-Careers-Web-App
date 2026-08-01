@extends('layouts.app')
@section('title', 'Manage Vacancies')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Vacancy Management</h1>
            <p class="text-gray-500 mt-1">Create, edit, and manage all job postings</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('hr.vacancies.create') }}" class="btn-solid-sky text-sm px-5 py-2.5 rounded-xl shadow-lg">
                <i class="fas fa-plus mr-2"></i> New Vacancy
            </a>
            <a href="{{ route('hr.reports.vacancy-progress') }}" class="border border-gray-300 text-gray-600 text-sm px-5 py-2.5 rounded-xl hover:bg-gray-50">
                <i class="fas fa-chart-bar mr-2"></i> Reports
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Total Vacancies</p>
            <p class="text-2xl font-extrabold text-[#0b3b5a] mt-1">{{ $vacancies->total() }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Published</p>
            <p class="text-2xl font-extrabold text-green-600 mt-1">{{ \App\Models\Vacancy::where('status','published')->count() }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Drafts</p>
            <p class="text-2xl font-extrabold text-yellow-600 mt-1">{{ \App\Models\Vacancy::where('status','draft')->count() }}</p>
        </div>
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Closed</p>
            <p class="text-2xl font-extrabold text-red-500 mt-1">{{ \App\Models\Vacancy::where('status','closed')->count() }}</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Vacancies Table -->
    <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b">
                    <tr>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Reference</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Title</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Department</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Status</th>
                        <th class="text-center px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Applications</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Closing Date</th>
                        <th class="text-right px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($vacancies as $vacancy)
                        <tr class="hover:bg-gray-50/50 transition-colors group">
                            <td class="px-5 py-4">
                                <span class="text-xs font-mono bg-gray-100 px-2 py-1 rounded text-gray-600">{{ $vacancy->vacancy_number }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <p class="font-semibold text-[#0b3b5a]">{{ $vacancy->title }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ ucfirst($vacancy->employment_type) }} · {{ $vacancy->duty_station }}</p>
                            </td>
                            <td class="px-5 py-4 text-gray-600">{{ $vacancy->department }}</td>
                            <td class="px-5 py-4">
                                @if($vacancy->status === 'published')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-50 text-green-700 rounded-full text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span> Published
                                    </span>
                                @elseif($vacancy->status === 'draft')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 bg-gray-400 rounded-full"></span> Draft
                                    </span>
                                @elseif($vacancy->status === 'closed')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-red-50 text-red-600 rounded-full text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 bg-red-400 rounded-full"></span> Closed
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-yellow-50 text-yellow-600 rounded-full text-xs font-semibold">
                                        <span class="w-1.5 h-1.5 bg-yellow-400 rounded-full"></span> {{ ucfirst($vacancy->status) }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                <span class="font-bold text-[#0b3b5a]">{{ $vacancy->applications_count ?? 0 }}</span>
                            </td>
                            <td class="px-5 py-4 text-gray-500 text-xs">
                                @if($vacancy->closing_date->isPast())
                                    <span class="text-red-500">Closed</span>
                                @else
                                    {{ $vacancy->closing_date->format('M d, Y') }}
                                    <span class="block text-gray-400">{{ $vacancy->closing_date->diffForHumans() }}</span>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('hr.vacancies.edit', $vacancy) }}" 
                                        class="p-2 text-gray-400 hover:text-[#0a7aa8] hover:bg-sky-50 rounded-lg transition-colors" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($vacancy->status === 'draft')
                                        <form action="{{ route('hr.vacancies.publish', $vacancy) }}" method="POST">
                                            @csrf
                                            <button class="p-2 text-gray-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-colors" title="Publish">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if($vacancy->status === 'published')
                                        <form action="{{ route('hr.vacancies.close', $vacancy) }}" method="POST">
                                            @csrf
                                            <button class="p-2 text-gray-400 hover:text-yellow-600 hover:bg-yellow-50 rounded-lg transition-colors" title="Close">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('vacancies.public.show', $vacancy) }}" 
                                        class="p-2 text-gray-400 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-colors" title="View Public" target="_blank">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <form action="{{ route('hr.vacancies.destroy', $vacancy) }}" method="POST" 
                                        onsubmit="return confirm('Archive this vacancy?')" class="inline">
                                        @csrf @method('DELETE')
                                        <button class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Archive">
                                            <i class="fas fa-archive"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                                <i class="fas fa-briefcase text-4xl mb-3 block"></i>
                                <p class="font-semibold">No vacancies found</p>
                                <a href="{{ route('hr.vacancies.create') }}" class="text-[#0a7aa8] hover:underline text-sm mt-2 inline-block">Create your first vacancy</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vacancies->hasPages())
            <div class="px-5 py-4 border-t bg-gray-50">{{ $vacancies->links() }}</div>
        @endif
    </div>
</section>
@endsection
