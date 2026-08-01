@extends('layouts.app')
@section('title', 'Job Alerts')
@section('content')

<section class="max-w-4xl mx-auto px-6 py-8">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-extrabold text-[#0b3b5a]">Job Alerts</h1>
        <p class="text-gray-500 mt-2">Get notified when new jobs matching your criteria are posted</p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-2xl p-8 shadow-sm border">
        <form action="{{ route('job.alerts.subscribe') }}" method="POST" class="space-y-6">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold text-[#0b3b5a] mb-2">
                    <i class="fas fa-envelope mr-2 text-[#0a7aa8]"></i> Email Address *
                </label>
                <input type="email" name="email" value="{{ Auth::user()->email ?? '' }}" required 
                    class="search-input w-full px-4 py-3 rounded-xl text-sm" placeholder="you@example.com">
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#0b3b5a] mb-2">
                    <i class="fas fa-search mr-2 text-[#0a7aa8]"></i> Job Keywords
                </label>
                <input type="text" name="keywords" 
                    class="search-input w-full px-4 py-3 rounded-xl text-sm" 
                    placeholder="e.g., Civil Engineer, Project Manager, Safety Officer">
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#0b3b5a] mb-3">
                    <i class="fas fa-tags mr-2 text-[#0a7aa8]"></i> Job Categories
                </label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach(['Engineering', 'Construction', 'HSE', 'Finance', 'Logistics', 'Management', 'TVET/Trade', 'Administration'] as $cat)
                        <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-xl hover:border-[#0a7aa8] cursor-pointer transition">
                            <input type="checkbox" name="categories[]" value="{{ strtolower($cat) }}" class="rounded border-gray-300 text-[#0a7aa8]">
                            <span class="text-sm text-gray-700">{{ $cat }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-[#0b3b5a] mb-3">
                    <i class="fas fa-clock mr-2 text-[#0a7aa8]"></i> Notification Frequency
                </label>
                <div class="flex gap-3">
                    @foreach(['daily' => 'Daily', 'weekly' => 'Weekly', 'monthly' => 'Monthly'] as $val => $label)
                        <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-xl hover:border-[#0a7aa8] cursor-pointer transition">
                            <input type="radio" name="frequency" value="{{ $val }}" {{ $val == 'weekly' ? 'checked' : '' }} class="text-[#0a7aa8]">
                            <span class="text-sm text-gray-700">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn-solid-sky w-full py-3 rounded-xl font-bold text-sm">
                <i class="fas fa-bell mr-2"></i> Subscribe to Job Alerts
            </button>
        </form>
    </div>
</section>
@endsection
