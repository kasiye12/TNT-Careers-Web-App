<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-t-4 border-t-blue-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Total Vacancies</p>
        <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ \App\Models\Vacancy::count() }}</p>
    </div>
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-t-4 border-t-green-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Applications</p>
        <p class="text-3xl font-extrabold text-green-600 mt-1">{{ \App\Models\Application::count() }}</p>
    </div>
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-t-4 border-t-purple-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Employees</p>
        <p class="text-3xl font-extrabold text-purple-600 mt-1">2,500+</p>
    </div>
    <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-t-4 border-t-yellow-500">
        <p class="text-xs text-gray-500 uppercase tracking-wide">Years Experience</p>
        <p class="text-3xl font-extrabold text-yellow-600 mt-1">20+</p>
    </div>
</div>
