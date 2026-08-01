@extends('layouts.app')

@section('title', 'Careers')

@section('content')
<!-- HERO SECTION -->
<section class="hero-gradient text-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4MCIgaGVpZ2h0PSI4MCIgdmlld0JveD0iMCAwIDQwIDQwIj48cGF0aCBkPSJNMjAgMjBhMTAgMTAgMCAwIDEgMTAgMTAgMTAgMTAgMCAwIDEtMTAgMTAgMTAgMTAgMCAwIDEtMTAtMTAgMTAgMTAgMCAwIDEgMTAtMTB6IiBmaWxsPSIjZmZmIiBvcGFjaXR5PSIwLjA1Ii8+PC9zdmc+');"></div>
    <div class="max-w-7xl mx-auto px-6 py-16 lg:py-24 relative">
        <div class="flex flex-col lg:flex-row lg:items-center gap-12">
            <div class="flex-1">
                <span class="inline-block bg-white/20 backdrop-blur-sm text-white/90 text-xs font-semibold px-4 py-1.5 rounded-full mb-4 border border-white/10">
                    <i class="fas fa-building mr-1.5"></i> Ethiopia's Leading Contractor
                </span>
                <h1 class="text-4xl lg:text-5xl font-extrabold leading-tight tracking-tight">
                    Build Your Career <br> 
                    <span class="text-[#b3dff5]">With The Best</span>
                </h1>
                <p class="text-white/80 text-lg mt-4 max-w-xl leading-relaxed">
                    Join TNT Construction & Trading PLC and work on landmark projects that shape the nation.
                </p>
                <div class="mt-8 flex flex-col sm:flex-row gap-3 max-w-xl">
                    <div class="relative flex-1">
                        <i class="fas fa-search absolute left-4 top-3.5 text-gray-400"></i>
                        <input type="text" id="heroSearch" placeholder="Search jobs by title or keyword..." class="search-input w-full pl-10 pr-4 py-3 rounded-xl text-gray-700 placeholder-gray-400 text-sm">
                    </div>
                    <a href="{{ route('vacancies.public.index') }}" class="btn-solid-sky font-semibold px-7 py-3 rounded-xl text-sm shadow-lg shadow-sky-600/20 whitespace-nowrap">
                        Search Jobs <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
            </div>
            <div class="flex-1 grid grid-cols-3 gap-4">
                <div class="stat-card glass-card rounded-2xl p-5 text-center backdrop-blur-sm border border-white/20">
                    <div class="text-3xl font-extrabold text-white">{{ \App\Models\Vacancy::where('status','published')->count() }}</div>
                    <div class="text-xs font-semibold text-white/80 uppercase tracking-wider">Open Jobs</div>
                </div>
                <div class="stat-card glass-card rounded-2xl p-5 text-center backdrop-blur-sm border border-white/20">
                    <div class="text-3xl font-extrabold text-white">GC-1</div>
                    <div class="text-xs font-semibold text-white/80 uppercase tracking-wider">Grade Level</div>
                </div>
                <div class="stat-card glass-card rounded-2xl p-5 text-center backdrop-blur-sm border border-white/20">
                    <div class="text-3xl font-extrabold text-white">20+</div>
                    <div class="text-xs font-semibold text-white/80 uppercase tracking-wider">Years Exp.</div>
                </div>
                <div class="stat-card glass-card rounded-2xl p-5 text-center backdrop-blur-sm border border-white/20 col-span-3 md:col-span-1">
                    <div class="text-3xl font-extrabold text-white">500+</div>
                    <div class="text-xs font-semibold text-white/80 uppercase tracking-wider">Employees</div>
                </div>
            </div>
        </div>
    </div>
    <div class="relative h-8 lg:h-12 bg-[#f8fafc] rounded-t-[3rem] -mb-0.5"></div>
</section>

<!-- FEATURED POSITIONS -->
<section class="max-w-7xl mx-auto px-6 -mt-4 relative z-10">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-[#0b3b5a] section-title">
            <i class="fas fa-star text-[#0a7aa8] mr-2.5"></i> Featured Positions
        </h2>
        <a href="{{ route('vacancies.public.index') }}" class="text-sm font-semibold text-[#0a7aa8] hover:text-[#0b5f85] flex items-center gap-1.5 transition">
            View All <i class="fas fa-arrow-right text-xs"></i>
        </a>
    </div>

    @php $jobs = \App\Models\Vacancy::where('status','published')->where('closing_date','>=',now())->latest()->take(6)->get(); @endphp
    <div class="grid md:grid-cols-2 gap-5">
        @forelse($jobs as $job)
        <div class="job-card bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70">
            <div class="flex items-start justify-between">
                <div>
                    <span class="badge-pill text-xs px-3 py-1 rounded-full inline-block mb-2"><i class="far fa-calendar-alt mr-1.5"></i> {{ ucfirst($job->employment_type) }}</span>
                    <h3 class="text-lg font-bold text-[#0b3b5a]">{{ $job->title }}</h3>
                    <p class="text-sm text-gray-500 mt-0.5">{{ $job->department }} · {{ $job->duty_station }}</p>
                </div>
                <span class="bg-[#eaf4fa] text-[#0a5f89] text-xs font-bold px-3 py-1.5 rounded-full">Closes {{ $job->closing_date->format('M d') }}</span>
            </div>
            <div class="mt-4 flex items-center gap-4 text-sm text-gray-500">
                <span><i class="fas fa-briefcase mr-1.5 text-[#0a7aa8]"></i> {{ ucfirst($job->employment_type) }}</span>
                <span><i class="fas fa-map-pin mr-1.5 text-[#0a7aa8]"></i> {{ $job->duty_station }}</span>
            </div>
            <div class="mt-4 flex justify-between items-center">
                <span class="text-xs text-gray-400">{{ $job->vacancy_number }}</span>
                <a href="{{ route('vacancies.public.show', $job) }}" class="text-sm font-semibold text-[#0a7aa8] hover:underline transition">Details →</a>
            </div>
        </div>
        @empty
        <div class="col-span-2 text-center py-12 text-gray-400">
            <i class="fas fa-briefcase text-4xl mb-3 block"></i> No open positions at this time.
        </div>
        @endforelse
    </div>
</section>

<!-- WHY TNT + CTA -->
<section class="max-w-7xl mx-auto px-6 mt-16">
    <div class="bg-white rounded-3xl p-8 lg:p-12 shadow-md border border-gray-100/80">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
            <div>
                <h2 class="text-2xl lg:text-3xl font-bold text-[#0b3b5a] section-title">
                    <i class="fas fa-medal text-[#0a7aa8] mr-2.5"></i> Why TNT Construction?
                </h2>
                <p class="text-gray-500 mt-2 max-w-2xl text-sm lg:text-base leading-relaxed">
                    Build your career with the best. We invest in our people with world-class training, 
                    safety-first culture, and impactful projects that define Ethiopia's skyline.
                </p>
                <div class="mt-5 flex flex-wrap gap-5 text-sm font-medium">
                    <span class="flex items-center gap-2"><i class="fas fa-check-circle text-[#0a7aa8]"></i> Grade 1 Contractor</span>
                    <span class="flex items-center gap-2"><i class="fas fa-check-circle text-[#0a7aa8]"></i> 20+ years excellence</span>
                    <span class="flex items-center gap-2"><i class="fas fa-check-circle text-[#0a7aa8]"></i> 500+ team members</span>
                </div>
            </div>
            <div class="flex-shrink-0 flex gap-3">
                <a href="{{ route('vacancies.public.index') }}" class="btn-solid-sky font-semibold px-8 py-3.5 rounded-xl shadow-lg shadow-sky-500/30 inline-flex items-center gap-2 transition">
                    Explore Jobs <i class="fas fa-arrow-right"></i>
                </a>
                <a href="{{ route('register') }}" class="btn-outline-sky font-semibold px-8 py-3.5 rounded-xl inline-flex items-center gap-2 transition">
                    Register <i class="fas fa-user-plus"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- TOOLS SECTION -->
<section class="max-w-7xl mx-auto px-6 mt-12 mb-16">
    <h2 class="text-2xl font-bold text-[#0b3b5a] section-title mb-6">
        <i class="fas fa-toolbox text-[#0a7aa8] mr-2.5"></i> Career Tools
    </h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <a href="{{ route('cv.generator') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70 hover:shadow-md hover:border-[#0a7aa8] transition text-center group">
                        <a href="{{ route('resume.builder') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70 hover:shadow-md hover:border-[#0a7aa8] transition text-center group">
                            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-indigo-100 transition">
                                <i class="fas fa-file-alt text-indigo-600 text-xl"></i>
                            </div>
                            <h4 class="font-semibold text-[#0b3b5a] text-sm">Resume Builder</h4>
                            <p class="text-xs text-gray-400 mt-1">Live preview editor</p>
                        </a>
            <div class="w-12 h-12 bg-sky-50 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-sky-100 transition">
                <i class="fas fa-file-pdf text-[#0a7aa8] text-xl"></i>
            </div>
            <h4 class="font-semibold text-[#0b3b5a] text-sm">CV Generator</h4>
            <p class="text-xs text-gray-400 mt-1">Create professional CV</p>
        </a>
        <a href="{{ route('salary.calculator') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70 hover:shadow-md hover:border-[#0a7aa8] transition text-center group">
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-green-100 transition">
                <i class="fas fa-calculator text-green-600 text-xl"></i>
            </div>
            <h4 class="font-semibold text-[#0b3b5a] text-sm">Salary Calculator</h4>
            <p class="text-xs text-gray-400 mt-1">Ethiopian payroll</p>
        </a>
        <a href="{{ route('interview.tips') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70 hover:shadow-md hover:border-[#0a7aa8] transition text-center group">
            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-purple-100 transition">
                <i class="fas fa-lightbulb text-purple-600 text-xl"></i>
            </div>
            <h4 class="font-semibold text-[#0b3b5a] text-sm">Interview Tips</h4>
            <p class="text-xs text-gray-400 mt-1">Preparation guide</p>
        </a>
        <a href="{{ route('job.alerts') }}" class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70 hover:shadow-md hover:border-[#0a7aa8] transition text-center group">
            <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center mx-auto mb-3 group-hover:bg-orange-100 transition">
                <i class="fas fa-bell text-orange-600 text-xl"></i>
            </div>
            <h4 class="font-semibold text-[#0b3b5a] text-sm">Job Alerts</h4>
            <p class="text-xs text-gray-400 mt-1">Get notifications</p>
        </a>
    </div>
</section>
@endsection
