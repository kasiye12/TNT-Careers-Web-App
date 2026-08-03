@extends('layouts.app')
@section('title', 'System Settings')
@section('content')

<section class="max-w-7xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">
            <i class="fas fa-cog text-gray-500 mr-2"></i> System Settings
        </h1>
        <p class="text-gray-500 mt-1">Configure system parameters - updates take effect immediately</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-6 text-sm flex items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- General Settings -->
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b flex items-center gap-3">
                <span class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center"><i class="fas fa-building text-[#0a7aa8]"></i></span>
                <h2 class="font-bold text-lg">General Settings</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.settings.update-general') }}" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Company Name *</label>
                            <input type="text" name="company_name" value="{{ \App\Helpers\SettingsHelper::companyName() }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Short Name *</label>
                            <input type="text" name="company_short" value="{{ cache('setting_company_short', 'TNT') }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Contact Email *</label>
                            <input type="email" name="contact_email" value="{{ \App\Helpers\SettingsHelper::contactEmail() }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Contact Phone *</label>
                            <input type="text" name="contact_phone" value="{{ \App\Helpers\SettingsHelper::contactPhone() }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Address *</label>
                            <input type="text" name="contact_address" value="{{ \App\Helpers\SettingsHelper::contactAddress() }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Company Description</label>
                            <textarea name="company_description" rows="2" class="search-input w-full px-4 py-3 rounded-xl text-sm">{{ cache('setting_company_description', 'Grade One General Contractor building Ethiopia\'s future.') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Employee Count *</label>
                            <input type="text" name="employee_count" value="{{ cache('setting_employee_count', '2,500+') }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Years Established *</label>
                            <input type="text" name="year_established" value="{{ cache('setting_year_established', '20+') }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Grade Level *</label>
                            <input type="text" name="grade_level" value="{{ cache('setting_grade_level', 'GC-1') }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                        </div>
                    </div>
                    
                    <!-- Social Links (OPTIONAL) -->
                    <div class="border-t pt-4 mt-4">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-share-alt text-gray-400"></i>
                            <label class="text-xs font-semibold text-gray-700">Social Media Links <span class="text-gray-400 font-normal">(Optional)</span></label>
                        </div>
                        <p class="text-xs text-gray-400 mb-3">Leave empty to hide social icons from the header</p>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs text-gray-500 mb-1"><i class="fab fa-facebook mr-1"></i> Facebook URL</label>
                                <input type="url" name="facebook_url" value="{{ cache('setting_facebook', '') }}" placeholder="Optional" class="search-input w-full px-4 py-3 rounded-xl text-sm">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1"><i class="fab fa-linkedin mr-1"></i> LinkedIn URL</label>
                                <input type="url" name="linkedin_url" value="{{ cache('setting_linkedin', '') }}" placeholder="Optional" class="search-input w-full px-4 py-3 rounded-xl text-sm">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1"><i class="fab fa-twitter mr-1"></i> Twitter/X URL</label>
                                <input type="url" name="twitter_url" value="{{ cache('setting_twitter', '') }}" placeholder="Optional" class="search-input w-full px-4 py-3 rounded-xl text-sm">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1"><i class="fab fa-instagram mr-1"></i> Instagram URL</label>
                                <input type="url" name="instagram_url" value="{{ cache('setting_instagram', '') }}" placeholder="Optional" class="search-input w-full px-4 py-3 rounded-xl text-sm">
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-solid-sky text-sm px-6 py-2.5 rounded-xl w-full">
                        <i class="fas fa-save mr-2"></i> Save General Settings
                    </button>
                </form>
            </div>
        </div>

        <!-- Recruitment Settings -->
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b flex items-center gap-3">
                <span class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center"><i class="fas fa-briefcase text-green-600"></i></span>
                <h2 class="font-bold text-lg">Recruitment Settings</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.settings.update-recruitment') }}" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Max Applications Per Candidate</label>
                        <input type="number" name="max_applications" value="{{ cache('max_applications', 10) }}" min="1" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Default Vacancy Duration (Days)</label>
                        <input type="number" name="vacancy_duration" value="{{ cache('vacancy_duration', 30) }}" min="1" required class="search-input w-full px-4 py-3 rounded-xl text-sm">
                    </div>
                    <div class="space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="auto_close_expired" value="1" {{ cache('auto_close_expired') ? 'checked' : '' }} class="w-5 h-5 rounded border-gray-300 text-[#0a7aa8]">
                            <span class="text-sm text-gray-700">Auto-close expired vacancies</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="send_notifications" value="1" checked class="w-5 h-5 rounded border-gray-300 text-[#0a7aa8]">
                            <span class="text-sm text-gray-700">Send email notifications</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="require_doc_verification" value="1" class="w-5 h-5 rounded border-gray-300 text-[#0a7aa8]">
                            <span class="text-sm text-gray-700">Require document verification</span>
                        </label>
                    </div>
                    <button type="submit" class="btn-solid-sky text-sm px-6 py-2.5 rounded-xl w-full">
                        <i class="fas fa-save mr-2"></i> Save Recruitment Settings
                    </button>
                </form>
            </div>
        </div>

        <!-- Email Configuration -->
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b flex items-center gap-3">
                <span class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center"><i class="fas fa-envelope text-purple-600"></i></span>
                <h2 class="font-bold text-lg">Email Configuration</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.settings.update-email') }}" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div class="grid grid-cols-2 gap-4">
                        <div><label class="block text-xs font-semibold text-gray-700 mb-1">SMTP Host *</label><input type="text" name="smtp_host" value="{{ config('mail.mailers.smtp.host') }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                        <div><label class="block text-xs font-semibold text-gray-700 mb-1">SMTP Port *</label><input type="number" name="smtp_port" value="{{ config('mail.mailers.smtp.port') }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                        <div class="col-span-2"><label class="block text-xs font-semibold text-gray-700 mb-1">Sender Email *</label><input type="email" name="sender_email" value="{{ config('mail.from.address') }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit" class="btn-solid-sky text-sm px-6 py-2.5 rounded-xl flex-1"><i class="fas fa-save mr-2"></i> Save</button>
                        <button type="submit" formaction="{{ route('admin.settings.test-email') }}" class="border border-gray-300 text-gray-600 text-sm px-6 py-2.5 rounded-xl hover:bg-gray-50"><i class="fas fa-paper-plane mr-2"></i> Test Email</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Document Settings -->
        <div class="bg-white rounded-2xl shadow-sm border overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b flex items-center gap-3">
                <span class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center"><i class="fas fa-file-alt text-orange-600"></i></span>
                <h2 class="font-bold text-lg">Document Settings</h2>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.settings.update-documents') }}" method="POST" class="space-y-4">
                    @csrf @method('PUT')
                    <div><label class="block text-xs font-semibold text-gray-700 mb-1">Max File Size (MB)</label><input type="number" name="max_file_size" value="{{ cache('max_file_size', 5) }}" min="1" max="50" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">Allowed File Types</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['pdf'=>'PDF', 'docx'=>'DOCX', 'jpg'=>'JPG', 'png'=>'PNG'] as $val=>$label)
                                <label class="flex items-center gap-2 px-4 py-2 bg-gray-50 rounded-xl cursor-pointer hover:bg-gray-100">
                                    <input type="checkbox" name="allowed_types[]" value="{{ $val }}" checked class="rounded border-gray-300 text-[#0a7aa8]">
                                    <span class="text-sm font-medium">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn-solid-sky text-sm px-6 py-2.5 rounded-xl w-full"><i class="fas fa-save mr-2"></i> Save Document Settings</button>
                </form>
            </div>
        </div>

    </div>
</section>
@endsection
