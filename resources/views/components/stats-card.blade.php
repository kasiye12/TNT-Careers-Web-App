<div class="stat-card bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm text-gray-500">{{ $title }}</p>
            <p class="text-3xl font-extrabold text-[#0b3b5a] mt-1">{{ $value }}</p>
        </div>
        <div class="w-12 h-12 bg-{{ $color }}-100 rounded-xl flex items-center justify-center">
            <i class="fas fa-{{ $icon }} text-{{ $color }}-600 text-xl"></i>
        </div>
    </div>
</div>
