<div class="grid grid-cols-2 md:grid-cols-4 gap-4">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-t-4 border-t-blue-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Vacancies</p>
        <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $stats['vacancies'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-t-4 border-t-green-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Applications</p>
        <p class="text-3xl font-extrabold text-green-600 mt-1">{{ $stats['applications'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-t-4 border-t-purple-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Users</p>
        <p class="text-3xl font-extrabold text-purple-600 mt-1">{{ $stats['users'] }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-t-4 border-t-yellow-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Active Jobs</p>
        <p class="text-3xl font-extrabold text-yellow-600 mt-1">{{ $stats['published'] }}</p>
    </div>
</div>
