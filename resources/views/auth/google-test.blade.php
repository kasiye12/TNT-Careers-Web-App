@extends('layouts.app')
@section('content')
<div class="max-w-2xl mx-auto px-6 py-12">
    <h1 class="text-2xl font-bold mb-6">Google OAuth Debug</h1>
    
    <div class="bg-white rounded-2xl p-6 shadow-sm border space-y-4">
        <div>
            <strong>APP_URL:</strong> {{ config('app.url') }}
        </div>
        <div>
            <strong>Google Client ID:</strong> {{ substr(config('services.google.client_id'), 0, 20) }}...
        </div>
        <div>
            <strong>Configured Redirect:</strong> {{ config('services.google.redirect') }}
        </div>
        <div>
            <strong>Generated Callback URL:</strong> {{ route('google.callback') }}
        </div>
        <div class="p-4 bg-yellow-50 rounded-xl">
            <p class="font-bold text-yellow-800">⚠️ Copy this EXACT URL to Google Console:</p>
            <p class="text-lg font-mono text-yellow-700 mt-2">{{ route('google.callback') }}</p>
        </div>
        <a href="{{ route('google.login') }}" class="btn-solid-sky inline-block px-6 py-3 rounded-xl">Try Google Login</a>
    </div>
</div>
@endsection
