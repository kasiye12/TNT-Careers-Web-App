@extends('layouts.app')
@section('title', 'Interview Tips')
@section('content')

<section class="max-w-5xl mx-auto px-6 py-8">
    <div class="text-center mb-12">
        <h1 class="text-3xl font-extrabold text-[#0b3b5a]">Interview Preparation Guide</h1>
        <p class="text-gray-500 mt-2">Tips and resources to help you succeed in your TNT Construction interview</p>
    </div>

    <!-- Tips Cards -->
    <div class="grid md:grid-cols-3 gap-6 mb-12">
        <div class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md transition">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center mb-4">
                <i class="fas fa-user-tie text-blue-600 text-xl"></i>
            </div>
            <h3 class="font-bold text-lg text-[#0b3b5a] mb-3">Before the Interview</h3>
            <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-1 text-xs"></i> Research TNT Construction and our projects</li>
                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-1 text-xs"></i> Prepare your documents (CV, certificates, licenses)</li>
                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-1 text-xs"></i> Practice common interview questions</li>
                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-1 text-xs"></i> Plan your route to arrive 15 minutes early</li>
                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-1 text-xs"></i> Dress professionally and appropriately</li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md transition">
            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center mb-4">
                <i class="fas fa-comments text-green-600 text-xl"></i>
            </div>
            <h3 class="font-bold text-lg text-[#0b3b5a] mb-3">During the Interview</h3>
            <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-1 text-xs"></i> Make eye contact and speak clearly</li>
                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-1 text-xs"></i> Use specific examples from your experience</li>
                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-1 text-xs"></i> Listen carefully and answer concisely</li>
                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-1 text-xs"></i> Ask thoughtful questions about the role</li>
                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-1 text-xs"></i> Show enthusiasm and confidence</li>
            </ul>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border hover:shadow-md transition">
            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center mb-4">
                <i class="fas fa-star text-purple-600 text-xl"></i>
            </div>
            <h3 class="font-bold text-lg text-[#0b3b5a] mb-3">After the Interview</h3>
            <ul class="space-y-2 text-sm text-gray-600">
                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-1 text-xs"></i> Send a thank-you email within 24 hours</li>
                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-1 text-xs"></i> Follow up after one week if no response</li>
                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-1 text-xs"></i> Reflect on what went well and what to improve</li>
                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-1 text-xs"></i> Keep applying to other positions</li>
                <li class="flex items-start gap-2"><i class="fas fa-check text-green-500 mt-1 text-xs"></i> Stay positive and patient</li>
            </ul>
        </div>
    </div>

    <!-- Common Questions -->
    <div class="bg-white rounded-2xl p-8 shadow-sm border mb-8">
        <h2 class="text-xl font-bold text-[#0b3b5a] mb-6 flex items-center gap-2">
            <span class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center"><i class="fas fa-question-circle text-[#0a7aa8]"></i></span>
            Common Interview Questions
        </h2>
        <div class="space-y-4">
            @php
                $questions = [
                    'Tell us about yourself and your experience in construction.',
                    'Why do you want to work at TNT Construction?',
                    'Describe a challenging project you worked on and how you handled it.',
                    'How do you ensure safety on a construction site?',
                    'Where do you see yourself in 5 years?',
                    'How do you handle tight deadlines and pressure?',
                    'What do you know about TNT Construction and our projects?',
                ];
            @endphp
            @foreach($questions as $q)
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <p class="font-semibold text-[#0b3b5a] text-sm">"{{ $q }}"</p>
                    <p class="text-xs text-gray-400 mt-2">
                        <i class="fas fa-lightbulb text-yellow-500 mr-1"></i> 
                        <strong>Tip:</strong> Use the STAR method (Situation, Task, Action, Result) to structure your answers.
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Construction Specific -->
    <div class="bg-white rounded-2xl p-8 shadow-sm border mb-8">
        <h2 class="text-xl font-bold text-[#0b3b5a] mb-6 flex items-center gap-2">
            <span class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center"><i class="fas fa-hard-hat text-orange-600"></i></span>
            Construction Industry Tips
        </h2>
        <div class="grid md:grid-cols-2 gap-4 text-sm">
            <div class="p-4 bg-orange-50 rounded-xl">
                <p class="font-semibold mb-2">Safety First</p>
                <p class="text-gray-600">Emphasize your commitment to safety. Mention any safety certifications or training you have completed.</p>
            </div>
            <div class="p-4 bg-blue-50 rounded-xl">
                <p class="font-semibold mb-2">Project Experience</p>
                <p class="text-gray-600">Be ready to discuss specific projects you've worked on - size, budget, timeline, and your role.</p>
            </div>
            <div class="p-4 bg-green-50 rounded-xl">
                <p class="font-semibold mb-2">Team Collaboration</p>
                <p class="text-gray-600">Construction is a team effort. Share examples of successful teamwork and leadership.</p>
            </div>
            <div class="p-4 bg-purple-50 rounded-xl">
                <p class="font-semibold mb-2">Technical Skills</p>
                <p class="text-gray-600">Highlight relevant technical skills: AutoCAD, project management software, quality control, etc.</p>
            </div>
        </div>
    </div>

    <!-- CTA -->
    <div class="text-center bg-gradient-to-r from-[#0b3b5a] to-[#0a7aa8] rounded-2xl p-8 text-white">
        <h2 class="text-2xl font-bold mb-2">Ready to Apply?</h2>
        <p class="text-white/80 mb-6">Browse our current openings and start your career at TNT Construction.</p>
        <a href="{{ route('vacancies.public.index') }}" class="inline-flex items-center bg-white text-[#0b3b5a] px-8 py-3 rounded-xl font-bold hover:bg-gray-100 transition">
            <i class="fas fa-search mr-2"></i> View Open Positions
        </a>
    </div>
</section>
@endsection
