@extends('layouts.app')
@section('title', 'Current Openings')

@section('content')
<!-- Hero -->
<section class="hero-gradient text-white relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-6 py-12 lg:py-16 relative">
        <h1 class="text-3xl lg:text-4xl font-extrabold tracking-tight">Current Openings</h1>
        <p class="text-white/80 mt-2 text-lg">Find your next career opportunity at TNT Construction</p>
    </div>
    <div class="relative h-6 lg:h-8 bg-[#f8fafc] rounded-t-[2rem] -mb-0.5"></div>
</section>

<!-- Search & Filters -->
<section class="max-w-7xl mx-auto px-6 -mt-3 relative z-10 mb-8">
    <form action="{{ route('vacancies.public.index') }}" method="GET" class="bg-white rounded-2xl p-4 shadow-md border border-gray-200/70">
        <div class="flex flex-col md:flex-row gap-3">
            <!-- Search Input -->
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="search-input w-full pl-10 pr-4 py-3 rounded-xl text-sm" 
                    placeholder="Search by title, department, or location...">
            </div>
            
            <!-- Category Filter -->
            <select name="category" class="border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-600 focus:border-[#0a7aa8] focus:ring-2 focus:ring-sky-100">
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept }}" {{ request('category') == $dept ? 'selected' : '' }}>{{ $dept }}</option>
                @endforeach
            </select>
            
            <!-- Location Filter -->
            <select name="location" class="border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-600 focus:border-[#0a7aa8] focus:ring-2 focus:ring-sky-100">
                <option value="">All Locations</option>
                <option value="head_office" {{ request('location') == 'head_office' ? 'selected' : '' }}>Head Office - Addis Ababa</option>
                <option value="project_site" {{ request('location') == 'project_site' ? 'selected' : '' }}>Project Sites</option>
            </select>
            
            <!-- Employment Type Filter -->
            <select name="type" class="border border-gray-200 rounded-xl px-4 py-3 text-sm text-gray-600 focus:border-[#0a7aa8] focus:ring-2 focus:ring-sky-100">
                <option value="">All Types</option>
                <option value="permanent" {{ request('type') == 'permanent' ? 'selected' : '' }}>Permanent</option>
                <option value="contract" {{ request('type') == 'contract' ? 'selected' : '' }}>Contract</option>
                <option value="project_based" {{ request('type') == 'project_based' ? 'selected' : '' }}>Project Based</option>
                <option value="temporary" {{ request('type') == 'temporary' ? 'selected' : '' }}>Temporary</option>
            </select>
            
            <!-- Submit & Clear -->
            <div class="flex gap-2">
                <button type="submit" class="btn-solid-sky text-sm px-6 py-3 rounded-xl whitespace-nowrap">
                    <i class="fas fa-filter mr-2"></i> Filter
                </button>
                @if(request()->anyFilled(['search', 'category', 'location', 'type']))
                    <a href="{{ route('vacancies.public.index') }}" class="border border-gray-300 text-gray-600 text-sm px-4 py-3 rounded-xl hover:bg-gray-50 whitespace-nowrap">
                        <i class="fas fa-times mr-1"></i> Clear
                    </a>
                @endif
            </div>
        </div>
        
        <!-- Active Filters -->
        @if(request()->anyFilled(['search', 'category', 'location', 'type']))
            <div class="flex flex-wrap gap-2 mt-3 pt-3 border-t border-gray-100">
                @if(request('search'))
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-sky-50 text-[#0a7aa8] rounded-full text-xs font-semibold">
                        🔍 {{ request('search') }}
                        <a href="{{ route('vacancies.public.index', array_merge(request()->except('search'), ['page' => null])) }}" class="text-gray-400 hover:text-red-500">×</a>
                    </span>
                @endif
                @if(request('category'))
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-semibold">
                        📂 {{ request('category') }}
                        <a href="{{ route('vacancies.public.index', array_merge(request()->except('category'), ['page' => null])) }}" class="text-gray-400 hover:text-red-500">×</a>
                    </span>
                @endif
                @if(request('location'))
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-semibold">
                        📍 {{ request('location') == 'head_office' ? 'Head Office' : 'Project Site' }}
                        <a href="{{ route('vacancies.public.index', array_merge(request()->except('location'), ['page' => null])) }}" class="text-gray-400 hover:text-red-500">×</a>
                    </span>
                @endif
                @if(request('type'))
                    <span class="inline-flex items-center gap-1 px-3 py-1 bg-orange-50 text-orange-700 rounded-full text-xs font-semibold">
                        💼 {{ ucfirst(request('type')) }}
                        <a href="{{ route('vacancies.public.index', array_merge(request()->except('type'), ['page' => null])) }}" class="text-gray-400 hover:text-red-500">×</a>
                    </span>
                @endif
            </div>
        @endif
    </form>
</section>

<!-- Jobs List -->
<section class="max-w-7xl mx-auto px-6">
    @if($vacancies->isEmpty())
        <div class="text-center py-20">
            <div class="text-6xl mb-4">🔍</div>
            <h3 class="text-xl font-bold text-[#0b3b5a] mb-2">No Jobs Found</h3>
            <p class="text-gray-500">
                @if(request()->anyFilled(['search', 'category', 'location', 'type']))
                    No jobs match your filters. <a href="{{ route('vacancies.public.index') }}" class="text-[#0a7aa8] font-semibold hover:underline">Clear filters</a>
                @else
                    No open positions at this time. Please check back later.
                @endif
            </p>
        </div>
    @else
        <p class="text-sm text-gray-500 mb-4">{{ $vacancies->total() }} job(s) found</p>
        <div class="space-y-4">
            @foreach($vacancies as $v)
                <div class="job-card bg-white rounded-2xl p-6 shadow-sm border flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-sky-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-briefcase text-[#0a7aa8] text-lg"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-lg font-bold text-[#0b3b5a]">{{ $v->title }}</h3>
                                <span class="badge-pill text-xs px-3 py-1 rounded-full">{{ ucfirst($v->employment_type) }}</span>
                            </div>
                            <p class="text-xs text-gray-400 mb-2">{{ $v->vacancy_number }}</p>
                            <div class="flex flex-wrap gap-3 text-sm text-gray-500">
                                <span><i class="fas fa-building mr-1 text-gray-300"></i>{{ $v->department }}</span>
                                <span><i class="fas fa-map-pin mr-1 text-gray-300"></i>{{ $v->duty_station }}</span>
                                <span><i class="far fa-calendar mr-1 text-gray-300"></i>Closes {{ $v->closing_date->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <a href="{{ route('vacancies.public.show', $v) }}" class="btn-outline-sky text-sm font-semibold px-5 py-2 rounded-xl transition">Details</a>
                        @auth
                            @if(Auth::user()->user_type === 'applicant')
                                <a href="{{ route('applicant.apply', $v) }}" class="btn-solid-sky text-sm font-semibold px-5 py-2 rounded-xl transition">Apply Now</a>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn-solid-sky text-sm font-semibold px-5 py-2 rounded-xl transition">Apply Now</a>
                        @endauth
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">{{ $vacancies->links() }}</div>
    @endif
</section>
@endsection
