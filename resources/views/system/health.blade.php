@extends('layouts.app')
@section('title', 'System Health')
@section('content')

<section class="max-w-5xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-extrabold text-[#0b3b5a] mb-8">
        <i class="fas fa-heartbeat text-red-500 mr-2"></i> System Health Check
    </h1>

    <div class="grid md:grid-cols-2 gap-6">
        <!-- PHP Version -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-2">PHP Version</h3>
            <p class="text-3xl font-extrabold text-green-600">{{ $checks['php_version'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Required: 8.2+</p>
        </div>

        <!-- Laravel Version -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-2">Laravel Version</h3>
            <p class="text-3xl font-extrabold text-blue-600">{{ $checks['laravel_version'] }}</p>
        </div>

        <!-- Database -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-2">Database</h3>
            @if($checks['database']['status'])
                <span class="inline-flex items-center gap-2 px-3 py-1 bg-green-50 text-green-700 rounded-full text-sm font-semibold">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span> Connected
                </span>
                <p class="text-sm text-gray-500 mt-2">Driver: {{ $checks['database']['driver'] }}</p>
            @else
                <span class="inline-flex items-center gap-2 px-3 py-1 bg-red-50 text-red-700 rounded-full text-sm font-semibold">
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span> Failed
                </span>
            @endif
        </div>

        <!-- Storage -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-2">Storage</h3>
            @if($checks['storage']['status'])
                <span class="inline-flex items-center gap-2 px-3 py-1 bg-green-50 text-green-700 rounded-full text-sm font-semibold">
                    <span class="w-2 h-2 bg-green-500 rounded-full"></span> Writable
                </span>
            @else
                <span class="inline-flex items-center gap-2 px-3 py-1 bg-red-50 text-red-700 rounded-full text-sm font-semibold">
                    <span class="w-2 h-2 bg-red-500 rounded-full"></span> Not Writable
                </span>
            @endif
            <p class="text-sm text-gray-500 mt-2">Free: {{ round($checks['storage']['free_space'] / 1024 / 1024 / 1024, 1) }} GB</p>
        </div>

        <!-- Mail -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-2">Mail Configuration</h3>
            <p class="text-sm"><strong>Driver:</strong> {{ $checks['mail']['driver'] }}</p>
            <p class="text-sm"><strong>Host:</strong> {{ $checks['mail']['host'] }}</p>
            <p class="text-sm"><strong>From:</strong> {{ $checks['mail']['from'] }}</p>
        </div>

        <!-- Cache -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border">
            <h3 class="font-bold text-lg mb-2">Cache</h3>
            <span class="inline-flex items-center gap-2 px-3 py-1 bg-green-50 text-green-700 rounded-full text-sm font-semibold">
                <span class="w-2 h-2 bg-green-500 rounded-full"></span> Active
            </span>
            <p class="text-sm text-gray-500 mt-2">Driver: {{ $checks['cache']['driver'] }}</p>
        </div>
    </div>
</section>
@endsection
