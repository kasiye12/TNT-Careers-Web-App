@extends('layouts.app')
@section('title', 'Edit Profile')
@section('content')

<section class="max-w-4xl mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Edit Profile</h1>
            <p class="text-gray-500 mt-1">Update your personal information</p>
        </div>
        <a href="{{ route('applicant.skills.edit') }}" class="btn-outline-sky text-sm px-4 py-2 rounded-xl">
            <i class="fas fa-cogs mr-1"></i> Edit Skills
        </a>
    </div>

    <form action="{{ route('applicant.profile.update') }}" method="POST" class="space-y-6">
        @csrf @method('PUT')
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-4"><i class="fas fa-user mr-2 text-[#0a7aa8]"></i>Name in English *</h3>
            <div class="grid grid-cols-3 gap-4">
                <div><label class="block text-sm font-semibold mb-1">First Name</label><input type="text" name="first_name_en" value="{{ $applicant->first_name_en }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div><label class="block text-sm font-semibold mb-1">Father's Name</label><input type="text" name="father_name_en" value="{{ $applicant->father_name_en }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div><label class="block text-sm font-semibold mb-1">Grandfather's Name</label><input type="text" name="grand_father_name_en" value="{{ $applicant->grand_father_name_en }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-4"><i class="fas fa-language mr-2 text-[#0a7aa8]"></i>ስም በአማርኛ</h3>
            <div class="grid grid-cols-3 gap-4">
                <div><label class="block text-sm font-semibold mb-1">ስም</label><input type="text" name="first_name_am" value="{{ $applicant->first_name_am }}" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div><label class="block text-sm font-semibold mb-1">የአባት ስም</label><input type="text" name="father_name_am" value="{{ $applicant->father_name_am }}" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div><label class="block text-sm font-semibold mb-1">የአያት ስም</label><input type="text" name="grand_father_name_am" value="{{ $applicant->grand_father_name_am }}" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-4"><i class="fas fa-id-card mr-2 text-[#0a7aa8]"></i>Personal Details</h3>
            <div class="grid grid-cols-3 gap-4">
                <div><label class="block text-sm font-semibold mb-1">Gender</label><select name="gender" required class="search-input w-full px-4 py-3 rounded-xl text-sm"><option value="male" {{ $applicant->gender=='male'?'selected':'' }}>Male</option><option value="female" {{ $applicant->gender=='female'?'selected':'' }}>Female</option></select></div>
                <div><label class="block text-sm font-semibold mb-1">Date of Birth</label><input type="date" name="dob" value="{{ $applicant->dob->format('Y-m-d') }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div><label class="block text-sm font-semibold mb-1">Nationality</label><input type="text" name="nationality" value="{{ $applicant->nationality }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-4"><i class="fas fa-map-pin mr-2 text-[#0a7aa8]"></i>Address</h3>
            <div class="grid grid-cols-2 gap-4">
                <div><label class="block text-sm font-semibold mb-1">Region</label><select name="region" required class="search-input w-full px-4 py-3 rounded-xl text-sm">@foreach($regions as $l=>$v)<option value="{{ $v }}" {{ $applicant->region==$v?'selected':'' }}>{{ $l }}</option>@endforeach</select></div>
                <div><label class="block text-sm font-semibold mb-1">City</label><input type="text" name="city" value="{{ $applicant->city }}" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div><label class="block text-sm font-semibold mb-1">Zone/Sub-City</label><input type="text" name="zone_subcity" value="{{ $applicant->zone_subcity }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div><label class="block text-sm font-semibold mb-1">Woreda</label><input type="text" name="woreda" value="{{ $applicant->woreda }}" required class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
                <div class="col-span-2"><label class="block text-sm font-semibold mb-1">Kebele/House No.</label><input type="text" name="kebele_house_no" value="{{ $applicant->kebele_house_no }}" class="search-input w-full px-4 py-3 rounded-xl text-sm"></div>
            </div>
        </div>

        <div class="flex justify-between items-center">
            <div class="flex gap-2">
                <a href="{{ route('applicant.skills.edit') }}" class="border border-gray-300 text-gray-600 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-50">
                    <i class="fas fa-cogs mr-1"></i> Skills & More
                </a>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('applicant.dashboard') }}" class="border border-gray-300 text-gray-600 px-6 py-3 rounded-xl font-semibold text-sm hover:bg-gray-50">Cancel</a>
                <button type="submit" class="btn-solid-sky px-8 py-3 rounded-xl font-bold text-sm">Update Profile</button>
            </div>
        </div>
    </form>
</section>
@endsection
