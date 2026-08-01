@extends('layouts.app')
@section('title', 'API Documentation')
@section('content')

<section class="max-w-5xl mx-auto px-6 py-8">
    <h1 class="text-3xl font-extrabold text-[#0b3b5a] mb-4">API Documentation</h1>
    <p class="text-gray-500 mb-8">TNT Construction RATS - REST API Reference</p>

    <!-- Get Vacancies -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border mb-6">
        <div class="flex items-center gap-3 mb-4">
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold">GET</span>
            <code class="text-sm font-mono bg-gray-100 px-3 py-1 rounded-lg">/api/v1/vacancies/latest</code>
        </div>
        <p class="text-sm text-gray-600 mb-4">Get the latest published job vacancies</p>
        
        <h4 class="font-semibold text-sm mb-2">Response:</h4>
        <pre class="bg-gray-900 text-green-400 p-4 rounded-xl text-xs overflow-x-auto">
[
    {
        "id": 1,
        "title": "Senior Project Engineer",
        "department": "Engineering Department",
        "duty_station": "Project Site - Building",
        "closing_date": "2026-12-31",
        "apply_url": "https://careers.tnt-constructions.com/vacancies/1"
    }
]</pre>
    </div>

    <!-- WordPress Integration -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border">
        <h3 class="font-bold text-lg mb-4">WordPress Integration</h3>
        <pre class="bg-gray-900 text-blue-400 p-4 rounded-xl text-xs overflow-x-auto">
// Add to WordPress functions.php or custom plugin
function tnt_fetch_jobs() {
    $response = wp_remote_get('https://careers.tnt-constructions.com/api/v1/vacancies/latest');
    $jobs = json_decode(wp_remote_retrieve_body($response));
    
    foreach($jobs as $job) {
        echo '<div class="job-card">';
        echo '<h3>' . esc_html($job->title) . '</h3>';
        echo '<p>' . esc_html($job->department) . '</p>';
        echo '<a href="' . esc_url($job->apply_url) . '">Apply Now</a>';
        echo '</div>';
    }
}
add_shortcode('tnt_jobs', 'tnt_fetch_jobs');</pre>
    </div>
</section>
@endsection
