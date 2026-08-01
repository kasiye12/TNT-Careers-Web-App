@extends('layouts.app')
@section('title', 'Offer Letter Preview')
@section('content')

<section class="max-w-4xl mx-auto px-6 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-2xl font-extrabold text-[#0b3b5a]">Offer Letter Preview</h1>
        <span class="px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">{{ ucfirst($offerLetter->status) }}</span>
    </div>

    <!-- Letter Preview -->
    <div class="bg-white rounded-2xl p-8 shadow-sm border mb-6 min-h-[600px]">
        <div class="text-center mb-8 border-b-2 border-gray-200 pb-6">
            <h2 class="text-xl font-extrabold text-[#0b3b5a]">TNT Construction & Trading PLC</h2>
            <p class="text-gray-500 text-sm">Grade One General Contractor</p>
            <h3 class="text-lg font-bold mt-4">OFFER OF EMPLOYMENT</h3>
        </div>

        <div class="space-y-4 text-sm">
            <p><strong>Reference:</strong> {{ $offerLetter->offer_reference_number }}</p>
            <p><strong>Date:</strong> {{ now()->format('F d, Y') }}</p>
            
            <p>Dear <strong>{{ $offerLetter->application->applicant->full_name_en }}</strong>,</p>
            
            <p>We are pleased to offer you the position of <strong>{{ $offerLetter->position_title }}</strong> 
               in the <strong>{{ $offerLetter->department }}</strong> at <strong>{{ $offerLetter->duty_station }}</strong>.</p>
            
            <div class="bg-gray-50 rounded-xl p-4 space-y-2 mt-4">
                <p><strong>Salary:</strong> {{ number_format($offerLetter->salary_amount, 2) }} {{ $offerLetter->salary_currency }}</p>
                <p><strong>Reporting Date:</strong> {{ $offerLetter->reporting_date->format('F d, Y') }}</p>
                <p><strong>Offer Valid Until:</strong> {{ $offerLetter->offer_expiry_date->format('F d, Y') }}</p>
                @if($offerLetter->benefits)
                    <p><strong>Benefits:</strong> {{ $offerLetter->benefits }}</p>
                @endif
            </div>
            
            <p>Please confirm your acceptance by the expiry date.</p>
            <p>Welcome to TNT Construction!</p>
        </div>

        <div class="grid grid-cols-2 gap-8 mt-12 pt-8 border-t">
            <div><p class="border-t border-gray-400 pt-2 text-sm">HR Manager</p></div>
            <div><p class="border-t border-gray-400 pt-2 text-sm">Employee Signature</p></div>
        </div>
    </div>

    <div class="flex justify-between">
        <a href="{{ route('applicant.applications.show', $offerLetter->application) }}" class="border border-gray-300 text-gray-600 px-6 py-3 rounded-xl font-semibold text-sm">Back</a>
        <div class="flex gap-3">
            <a href="{{ route('offer-letters.view', $offerLetter) }}" class="bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold text-sm" target="_blank">
                <i class="fas fa-file-pdf mr-2"></i> View PDF
            </a>
            @if($offerLetter->status === 'draft')
                <form action="{{ route('hr.offer-letters.send', $offerLetter) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-solid-sky px-6 py-3 rounded-xl font-bold text-sm">
                        <i class="fas fa-paper-plane mr-2"></i> Send to Candidate
                    </button>
                </form>
            @endif
        </div>
    </div>
</section>
@endsection
