@extends('layouts.app')
@section('title', 'System Settings')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">System Settings</h1>
        <p class="text-gray-500 mt-1">Configure system parameters and preferences</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- General Settings -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h2 class="font-bold text-lg mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center"><i class="fas fa-cog text-[#0a7aa8]"></i></span>
                General Settings
            </h2>
            <form class="space-y-4">
                <div><label class="block text-sm font-semibold mb-1">Company Name</label><input type="text" value="TNT Construction & Trading PLC" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div><label class="block text-sm font-semibold mb-1">Contact Email</label><input type="email" value="hr@tnt-constructions.com" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div><label class="block text-sm font-semibold mb-1">Contact Phone</label><input type="text" value="+251-11-XXXXXXX" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <button class="btn-solid-sky text-sm px-6 py-2.5 rounded-xl">Save Changes</button>
            </form>
        </div>

        <!-- Recruitment Settings -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h2 class="font-bold text-lg mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center"><i class="fas fa-briefcase text-green-600"></i></span>
                Recruitment Settings
            </h2>
            <form class="space-y-4">
                <div><label class="block text-sm font-semibold mb-1">Max Applications Per Candidate</label><input type="number" value="10" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div><label class="block text-sm font-semibold mb-1">Default Vacancy Duration (Days)</label><input type="number" value="30" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div class="space-y-2">
                    <label class="flex items-center gap-2"><input type="checkbox" checked class="rounded border-gray-300 text-[#0a7aa8]"> Auto-close expired vacancies</label>
                    <label class="flex items-center gap-2"><input type="checkbox" checked class="rounded border-gray-300 text-[#0a7aa8]"> Send email notifications</label>
                    <label class="flex items-center gap-2"><input type="checkbox" class="rounded border-gray-300 text-[#0a7aa8]"> Require document verification</label>
                </div>
                <button class="btn-solid-sky text-sm px-6 py-2.5 rounded-xl">Save Changes</button>
            </form>
        </div>

        <!-- Email Configuration -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h2 class="font-bold text-lg mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center"><i class="fas fa-envelope text-purple-600"></i></span>
                Email Configuration
            </h2>
            <form class="space-y-4">
                <div><label class="block text-sm font-semibold mb-1">SMTP Host</label><input type="text" value="smtp.gmail.com" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div><label class="block text-sm font-semibold mb-1">SMTP Port</label><input type="number" value="587" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div><label class="block text-sm font-semibold mb-1">Sender Email</label><input type="email" value="noreply@tnt-constructions.com" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <button class="btn-solid-sky text-sm px-6 py-2.5 rounded-xl">Save & Test</button>
            </form>
        </div>

        <!-- Document Settings -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h2 class="font-bold text-lg mb-4 flex items-center gap-2">
                <span class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center"><i class="fas fa-file-alt text-orange-600"></i></span>
                Document Settings
            </h2>
            <form class="space-y-4">
                <div><label class="block text-sm font-semibold mb-1">Max File Size (MB)</label><input type="number" value="5" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Allowed File Types</label>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-sky-100 text-[#0a7aa8] rounded-full text-xs font-semibold">PDF</span>
                        <span class="px-3 py-1 bg-sky-100 text-[#0a7aa8] rounded-full text-xs font-semibold">DOCX</span>
                        <span class="px-3 py-1 bg-sky-100 text-[#0a7aa8] rounded-full text-xs font-semibold">JPG</span>
                        <span class="px-3 py-1 bg-sky-100 text-[#0a7aa8] rounded-full text-xs font-semibold">PNG</span>
                    </div>
                </div>
                <button class="btn-solid-sky text-sm px-6 py-2.5 rounded-xl">Save Changes</button>
            </form>
        </div>
    </div>
</section>
@endsection
