<div class="job-card bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70 hover:shadow-md transition group">
    <div class="flex items-start justify-between mb-3">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-sky-50 rounded-xl flex items-center justify-center">
                <i class="fas fa-briefcase text-[#0a7aa8]"></i>
            </div>
            <div>
                <h3 class="font-bold text-[#0b3b5a] group-hover:text-[#0a7aa8] transition-colors">{{ $vacancy->title }}</h3>
                <p class="text-xs text-gray-400">{{ $vacancy->vacancy_number }}</p>
            </div>
        </div>
        <span class="badge-pill text-xs px-3 py-1 rounded-full">{{ ucfirst($vacancy->employment_type) }}</span>
    </div>
    
    <div class="flex flex-wrap gap-3 text-sm text-gray-500 mb-4">
        <span><i class="fas fa-building mr-1 text-gray-300"></i> {{ $vacancy->department }}</span>
        <span><i class="fas fa-map-pin mr-1 text-gray-300"></i> {{ $vacancy->duty_station }}</span>
        <span><i class="far fa-calendar mr-1 text-gray-300"></i> Closes {{ $vacancy->closing_date->format('M d, Y') }}</span>
    </div>
    
    <div class="flex items-center justify-between pt-3 border-t border-gray-50">
        <div class="flex items-center gap-3 text-xs text-gray-400">
            <span><i class="far fa-eye mr-1"></i> {{ number_format($vacancy->views_count ?? 0) }} views</span>
            <span><i class="far fa-file-alt mr-1"></i> {{ $vacancy->applications_count ?? 0 }} applications</span>
        </div>
        <a href="{{ route('vacancies.public.show', $vacancy) }}" class="text-[#0a7aa8] font-semibold text-sm hover:underline">
            View Details →
        </a>
    </div>
</div>
