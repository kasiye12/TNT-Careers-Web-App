@extends('layouts.app')
@section('title', $vacancy->title)

@section('content')

<!-- Hero Header -->
<section class="hero-gradient text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI2MCIgaGVpZ2h0PSI2MCIgdmlld0JveD0iMCAwIDYwIDYwIj48cGF0aCBkPSJNMzAgMzBhMTUgMTUgMCAwIDEgMTUgMTUgMTUgMTUgMCAwIDEtMTUgMTUgMTUgMTUgMCAwIDEtMTUtMTUgMTUgMTUgMCAwIDEgMTUtMTV6IiBmaWxsPSIjZmZmIiBvcGFjaXR5PSIwLjAzIi8+PC9zdmc+');"></div>
    <div class="max-w-5xl mx-auto px-6 py-10 lg:py-14 relative">
        <!-- Breadcrumb -->
        <nav class="flex items-center gap-2 text-sm text-white/60 mb-4">
            <a href="/" class="hover:text-white transition">Home</a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <a href="{{ route('vacancies.public.index') }}" class="hover:text-white transition">Jobs</a>
            <i class="fas fa-chevron-right text-[10px]"></i>
            <span class="text-white/80">{{ $vacancy->title }}</span>
        </nav>
        
        <div class="flex flex-wrap items-start gap-3 mb-3">
            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-semibold">{{ ucfirst($vacancy->employment_type) }}</span>
            <span class="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-full text-xs font-semibold">{{ $vacancy->vacancy_number }}</span>
            @if($vacancy->construction_experience_required)
                <span class="px-3 py-1 bg-yellow-500/30 backdrop-blur-sm rounded-full text-xs font-semibold">Construction Exp Required</span>
            @endif
        </div>
        
        <h1 class="text-3xl lg:text-4xl font-extrabold tracking-tight mb-4">{{ $vacancy->title }}</h1>
        
        <div class="flex flex-wrap gap-4 text-white/80 text-sm">
            <span class="flex items-center gap-1.5"><i class="fas fa-building"></i> {{ $vacancy->department }}</span>
            <span class="flex items-center gap-1.5"><i class="fas fa-map-pin"></i> {{ $vacancy->duty_station }}</span>
            <span class="flex items-center gap-1.5"><i class="fas fa-clock"></i> {{ ucfirst($vacancy->employment_type) }}</span>
            <span class="flex items-center gap-1.5"><i class="far fa-calendar"></i> Closes {{ $vacancy->closing_date->format('M d, Y') }}</span>
            <span class="flex items-center gap-1.5"><i class="fas fa-users"></i> {{ $vacancy->positions_count }} position(s)</span>
        </div>
    </div>
    <div class="relative h-6 lg:h-8 bg-[#f8fafc] rounded-t-[2rem] -mb-0.5"></div>
</section>

<!-- Main Content -->
<section class="max-w-5xl mx-auto px-6 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- LEFT: Job Details -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Job Description -->
            @if($vacancy->description_en)
            <div class="bg-white rounded-2xl p-6 lg:p-8 shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-[#0b3b5a] mb-4 flex items-center gap-3">
                    <span class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-file-alt text-blue-600"></i>
                    </span>
                    Job Description
                </h2>
                <div class="prose max-w-none text-gray-600 leading-relaxed text-sm">
                    {!! nl2br(e($vacancy->description_en)) !!}
                </div>
            </div>
            @endif

            <!-- Key Responsibilities -->
            @if($vacancy->responsibilities_en)
            <div class="bg-white rounded-2xl p-6 lg:p-8 shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-[#0b3b5a] mb-4 flex items-center gap-3">
                    <span class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-tasks text-green-600"></i>
                    </span>
                    Key Responsibilities
                </h2>
                <div class="prose max-w-none text-gray-600 leading-relaxed text-sm">
                    {!! nl2br(e($vacancy->responsibilities_en)) !!}
                </div>
            </div>
            @endif

            <!-- Requirements -->
            @if($vacancy->requirements_en)
            <div class="bg-white rounded-2xl p-6 lg:p-8 shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-[#0b3b5a] mb-4 flex items-center gap-3">
                    <span class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-purple-600"></i>
                    </span>
                    Requirements & Qualifications
                </h2>
                <div class="prose max-w-none text-gray-600 leading-relaxed text-sm">
                    {!! nl2br(e($vacancy->requirements_en)) !!}
                </div>
            </div>
            @endif

            <!-- Amharic Description -->
            @if($vacancy->description_am)
            <div class="bg-white rounded-2xl p-6 lg:p-8 shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-[#0b3b5a] mb-4 flex items-center gap-3">
                    <span class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-language text-orange-600"></i>
                    </span>
                    የስራ መግለጫ (Amharic)
                </h2>
                <div class="text-gray-600 leading-relaxed text-sm text-right" dir="rtl">
                    {!! nl2br(e($vacancy->description_am)) !!}
                </div>
            </div>
            @endif
        </div>

        <!-- RIGHT: Sidebar -->
        <div class="space-y-6">
            
            <!-- Job Summary Card -->
            <div class="bg-white rounded-2xl p-6 shadow-md border border-gray-100 sticky top-24">
                <h3 class="text-lg font-bold text-[#0b3b5a] mb-5 flex items-center gap-2">
                    <span class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-info-circle text-[#0a7aa8] text-sm"></i>
                    </span>
                    Job Summary
                </h3>
                
                <dl class="space-y-4">
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <dt class="text-sm text-gray-500 flex items-center gap-2">
                            <i class="fas fa-calendar-alt text-gray-300 w-4"></i> Posted
                        </dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $vacancy->opening_date->format('M d, Y') }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <dt class="text-sm text-gray-500 flex items-center gap-2">
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <dt class="text-sm text-gray-500 flex items-center gap-2">
                            <i class="fas fa-eye text-gray-300 w-4"></i> Views
                        </dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ number_format($vacancy->views_count) }}</dd>
                    </div>
                            <i class="fas fa-hourglass-end text-gray-300 w-4"></i> Deadline
                        </dt>
                        <dd class="text-sm font-bold text-red-500">{{ $vacancy->closing_date->format('M d, Y') }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <dt class="text-sm text-gray-500 flex items-center gap-2">
                            <i class="fas fa-briefcase text-gray-300 w-4"></i> Experience
                        </dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $vacancy->min_years_experience }}+ Years</dd>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <dt class="text-sm text-gray-500 flex items-center gap-2">
                            <i class="fas fa-graduation-cap text-gray-300 w-4"></i> Education
                        </dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ strtoupper(str_replace('_', ' ', $vacancy->min_education_level)) }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <dt class="text-sm text-gray-500 flex items-center gap-2">
                            <i class="fas fa-users text-gray-300 w-4"></i> Positions
                        </dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $vacancy->positions_count }} Open</dd>
                    </div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <dt class="text-sm text-gray-500 flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-gray-300 w-4"></i> Location
                        </dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ $vacancy->duty_station_category == 'head_office' ? 'Head Office' : 'Project Site' }}</dd>
                    </div>
                    <div class="flex items-center justify-between py-2">
                        <dt class="text-sm text-gray-500 flex items-center gap-2">
                            <i class="fas fa-money-bill-wave text-gray-300 w-4"></i> Salary
                        </dt>
                        <dd class="text-sm font-semibold text-gray-900">
                            @if($vacancy->salary_type == 'negotiable')
                                Negotiable (Attractive)
                            @elseif($vacancy->salary_type == 'scale')
                                As per Company Scale
                            @else
                                {{ number_format($vacancy->salary_amount) }} {{ $vacancy->salary_currency }}
                            @endif
                        </dd>
                    </div>
                </dl>

                <!-- Apply Button -->
                <div class="mt-6 space-y-3">
                    @auth
                        @if(Auth::user()->user_type === 'applicant')
                            <a href="{{ route('applicant.apply', $vacancy) }}" 
                                class="btn-solid-sky w-full text-center justify-center py-3.5 rounded-xl font-bold text-sm shadow-lg shadow-sky-500/25">
                                <i class="fas fa-paper-plane mr-2"></i> Apply for this Position
                            </a>
                        @elseif(in_array(Auth::user()->user_type, ['admin', 'hr_manager']))
                            <div class="bg-yellow-50 rounded-xl p-3 text-center text-sm text-yellow-700">
                                <i class="fas fa-info-circle mr-1"></i> Admin/HR account - 
                                <a href="{{ route('hr.vacancies.edit', $vacancy) }}" class="font-semibold text-[#0a7aa8] hover:underline">Manage</a>
                            </div>
                        @endif
                    @else
                        <a href="{{ route('login') }}" 
                            class="btn-solid-sky w-full text-center justify-center py-3.5 rounded-xl font-bold text-sm shadow-lg shadow-sky-500/25">
                            <i class="fas fa-sign-in-alt mr-2"></i> Sign In to Apply
                        </a>
                        <p class="text-center text-xs text-gray-400">
                            Don't have an account? 
                            <a href="{{ route('register') }}" class="text-[#0a7aa8] font-semibold hover:underline">Register here</a>
                        </p>
                    @endauth
                </div>

                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <dt class="text-sm text-gray-500 flex items-center gap-2">
                            <i class="fas fa-eye text-gray-300 w-4"></i> Views
                        </dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ number_format($vacancy->views_count) }}</dd>
                    </div>
                <!-- Deadline Alert -->
                @if($vacancy->closing_date->diffInDays(now()) <= 7)
                    <div class="mt-4 p-3 bg-red-50 rounded-xl flex items-start gap-2">
                        <i class="fas fa-exclamation-circle text-red-500 mt-0.5"></i>
                        <div>
                    <div class="flex items-center justify-between py-2 border-b border-gray-50">
                        <dt class="text-sm text-gray-500 flex items-center gap-2">
                            <i class="fas fa-eye text-gray-300 w-4"></i> Views
                        </dt>
                        <dd class="text-sm font-semibold text-gray-900">{{ number_format($vacancy->views_count) }}</dd>
                    </div>
                            <p class="text-sm font-semibold text-red-600">Hurry! Deadline Approaching</p>
                            <p class="text-xs text-red-500">Only {{ $vacancy->closing_date->diffInDays(now()) }} days left</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- About Company Card -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-[#0b3b5a] mb-3 flex items-center gap-2">
                    <span class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-building text-[#0a7aa8] text-sm"></i>
                    </span>
                    About TNT Construction
                </h3>
                <p class="text-sm text-gray-500 leading-relaxed">
                    TNT Construction & Trading PLC is a Grade One General Contractor with over 20 years of experience building Ethiopia's infrastructure. We specialize in large-scale construction projects.
                </p>
                <div class="flex flex-wrap gap-2 mt-3">
                    <span class="text-xs text-green-600 font-semibold">✅ GC-1 Certified</span>
                    <span class="text-xs text-green-600 font-semibold">✅ 20+ Years</span>
                    <span class="text-xs text-green-600 font-semibold">✅ 2,500+ Employees</span>
                </div>
            </div>

            <!-- Share Job -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                <h3 class="font-bold text-sm text-[#0b3b5a] mb-3">Share This Job</h3>
                <div class="flex gap-2">
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}" target="_blank" 
                        class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center hover:bg-blue-100 transition text-blue-600">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url={{ url()->current() }}&text={{ urlencode($vacancy->title) }}" target="_blank"
                        class="w-10 h-10 bg-sky-50 rounded-lg flex items-center justify-center hover:bg-sky-100 transition text-sky-600">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ url()->current() }}" target="_blank"
                        class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center hover:bg-blue-100 transition text-blue-700">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                    <a href="https://wa.me/?text={{ urlencode($vacancy->title . ' - ' . url()->current()) }}" target="_blank"
                        class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center hover:bg-green-100 transition text-green-600">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Similar Jobs -->
    @php
        $similarJobs = \App\Models\Vacancy::where('status', 'published')
            ->where('id', '!=', $vacancy->id)
            ->where('closing_date', '>=', now())
            ->where(function($q) use ($vacancy) {
                $q->where('job_category', $vacancy->job_category)
                  ->orWhere('department', $vacancy->department);
            })
            ->latest()
            ->take(3)
            ->get();
    @endphp

    @if($similarJobs->isNotEmpty())
    <div class="mt-12">
        <h2 class="text-xl font-bold text-[#0b3b5a] mb-6">Similar Jobs You Might Like</h2>
        <div class="grid md:grid-cols-3 gap-4">
            @foreach($similarJobs as $job)
                <a href="{{ route('vacancies.public.show', $job) }}" 
                    class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100 hover:shadow-md hover:border-[#0a7aa8] transition group">
                    <div class="flex items-start justify-between mb-3">
                        <div class="w-10 h-10 bg-sky-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-briefcase text-[#0a7aa8]"></i>
                        </div>
                        <span class="text-xs px-2 py-1 bg-green-50 text-green-600 rounded-full font-semibold">{{ ucfirst($job->employment_type) }}</span>
                    </div>
                    <h3 class="font-bold text-[#0b3b5a] group-hover:text-[#0a7aa8] transition-colors mb-2">{{ $job->title }}</h3>
                    <p class="text-sm text-gray-500"><i class="fas fa-map-pin mr-1 text-gray-300"></i> {{ $job->duty_station }}</p>
                    <p class="text-xs text-gray-400 mt-2">Closes {{ $job->closing_date->format('M d, Y') }}</p>
                </a>
            @endforeach
        </div>
    </div>
    @endif
</section>
@endsection
