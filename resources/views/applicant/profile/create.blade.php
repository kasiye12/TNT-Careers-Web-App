@extends('layouts.app')
@section('title', 'Complete Profile')
@section('content')

<section class="max-w-4xl mx-auto px-6 py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Complete Your Profile</h1>
        <p class="text-gray-500 mt-1">Fill in your personal information to start applying for positions.</p>
    </div>

    <form action="{{ route('applicant.profile.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <!-- English Names -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70">
            <h3 class="font-bold text-lg text-[#0b3b5a] mb-4"><i class="fas fa-user mr-2 text-[#0a7aa8]"></i> Name in English *</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div><label class="block text-sm font-semibold text-gray-700 mb-1">First Name</label><input type="text" name="first_name_en" value="{{ old('first_name_en') }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1">Father's Name</label><input type="text" name="father_name_en" value="{{ old('father_name_en') }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1">Grandfather's Name</label><input type="text" name="grand_father_name_en" value="{{ old('grand_father_name_en') }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
            </div>
        </div>

        <!-- Amharic Names -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70">
            <h3 class="font-bold text-lg text-[#0b3b5a] mb-4"><i class="fas fa-language mr-2 text-[#0a7aa8]"></i> ስም በአማርኛ (Name in Amharic)</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div><label class="block text-sm font-semibold text-gray-700 mb-1">ስም</label><input type="text" name="first_name_am" value="{{ old('first_name_am') }}" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1">የአባት ስም</label><input type="text" name="father_name_am" value="{{ old('father_name_am') }}" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1">የአያት ስም</label><input type="text" name="grand_father_name_am" value="{{ old('grand_father_name_am') }}" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
            </div>
        </div>

        <!-- Personal Details -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70">
            <h3 class="font-bold text-lg text-[#0b3b5a] mb-4"><i class="fas fa-id-card mr-2 text-[#0a7aa8]"></i> Personal Details *</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div><label class="block text-sm font-semibold text-gray-700 mb-1">Gender</label><select name="gender" required class="search-input w-full px-4 py-3 rounded-xl text-sm"><option value="">Select</option><option value="male">Male</option><option value="female">Female</option></select></div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1">Date of Birth</label><input type="date" name="dob" value="{{ old('dob') }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1">Nationality</label><input type="text" name="nationality" value="{{ old('nationality', 'Ethiopian') }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
            </div>
        </div>

        <!-- Address -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-200/70">
            <h3 class="font-bold text-lg text-[#0b3b5a] mb-4"><i class="fas fa-map-pin mr-2 text-[#0a7aa8]"></i> Address *</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold text-gray-700 mb-1">Region</label><select name="region" required class="search-input w-full px-4 py-3 rounded-xl text-sm"><option value="">Select</option>@foreach($regions as $l=>$v)<option value="{{ $v }}">{{ $l }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1">City</label><input type="text" name="city" value="{{ old('city') }}" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1">Zone / Sub-City</label><input type="text" name="zone_subcity" value="{{ old('zone_subcity') }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div><label class="block text-sm font-semibold text-gray-700 mb-1">Woreda</label><input type="text" name="woreda" value="{{ old('woreda') }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div class="md:col-span-2"><label class="block text-sm font-semibold text-gray-700 mb-1">Kebele / House No.</label><input type="text" name="kebele_house_no" value="{{ old('kebele_house_no') }}" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
            </div>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('applicant.dashboard') }}" class="border border-gray-300 text-gray-600 px-6 py-3 rounded-xl font-semibold hover:bg-gray-50">Cancel</a>
            <button type="submit" class="btn-solid-sky px-8 py-3 rounded-xl font-bold">Save & Continue</button>
        </div>
    </form>
</section>
@endsection
