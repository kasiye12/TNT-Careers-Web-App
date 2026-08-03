<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-t-4 border-t-blue-500">
        <p class="text-xs text-gray-500 uppercase">Vacancies</p>
        <p class="text-3xl font-extrabold text-gray-900">{{ $stats['vacancies'] }}</p>
    </div>
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-t-4 border-t-green-500">
        <p class="text-xs text-gray-500 uppercase">Applications</p>
        <p class="text-3xl font-extrabold text-green-600">{{ $stats['applications'] }}</p>
    </div>
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-t-4 border-t-yellow-500">
        <p class="text-xs text-gray-500 uppercase">Shortlisted</p>
        <p class="text-3xl font-extrabold text-yellow-600">{{ $stats['shortlisted'] }}</p>
    </div>
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-t-4 border-t-purple-500">
        <p class="text-xs text-gray-500 uppercase">Selected</p>
        <p class="text-3xl font-extrabold text-purple-600">{{ $stats['selected'] }}</p>
    </div>
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-t-4 border-t-orange-500">
        <p class="text-xs text-gray-500 uppercase">Users</p>
        <p class="text-3xl font-extrabold text-orange-600">{{ $stats['users'] }}</p>
    </div>
</div>
