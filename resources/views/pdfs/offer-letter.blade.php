<!DOCTYPE html><html><head><meta charset="utf-8"><title>Offer Letter</title>
<style>body{font-family:DejaVu Sans,sans-serif;font-size:12px;line-height:1.6}h1{color:#1a365d;text-align:center}table{width:100%;border-collapse:collapse;margin:15px 0}td{padding:5px;border-bottom:1px solid #eee}.sign{margin-top:50px;display:flex;justify-content:space-between}.sign>div{width:45%}.sign-line{border-top:1px solid #000;margin-top:40px;padding-top:5px}</style></head>
<body>
<h1>TNT Construction & Trading PLC</h1>
<h2 style="text-align:center">OFFER OF EMPLOYMENT</h2>
<p><strong>Ref:</strong> {{ $offer_reference_number }} | <strong>Date:</strong> {{ now()->format('F d, Y') }}</p>
<p>Dear <strong>{{ $application->applicant->full_name_en }}</strong>,</p>
<p>We are pleased to offer you the position of <strong>{{ $position_title }}</strong> at <strong>{{ $duty_station }}</strong>.</p>

<table>
<tr><td><strong>Position:</strong></td><td>{{ $position_title }}</td></tr>
<tr><td><strong>Department:</strong></td><td>{{ $department }}</td></tr>
<tr><td><strong>Salary:</strong></td><td>{{ number_format($salary_amount,2) }} {{ $salary_currency }}</td></tr>
<tr><td><strong>Reporting Date:</strong></td><td>{{ \Carbon\Carbon::parse($reporting_date)->format('F d, Y') }}</td></tr>
<tr><td><strong>Offer Expires:</strong></td><td>{{ \Carbon\Carbon::parse($offer_expiry_date)->format('F d, Y') }}</td></tr>
</table>

@if($benefits)<p><strong>Benefits:</strong> {{ $benefits }}</p>@endif

<div class="sign">
<div><div class="sign-line">HR Manager, TNT Construction</div></div>
<div><div class="sign-line">Employee Signature & Date</div></div>
</div>
</body></html>
