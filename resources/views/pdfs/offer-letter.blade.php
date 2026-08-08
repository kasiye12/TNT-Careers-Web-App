<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Offer Letter - {{ $offer_reference_number ?? 'N/A' }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #1a202c;
            margin: 40px;
        }
        .letterhead {
            text-align: center;
            border-bottom: 3px solid #1a365d;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .letterhead h1 {
            color: #1a365d;
            font-size: 22px;
            margin: 0 0 5px 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .letterhead h2 {
            color: #4a5568;
            font-size: 14px;
            margin: 0;
            font-weight: normal;
        }
        .reference {
            margin-bottom: 20px;
            font-size: 11px;
            color: #4a5568;
        }
        .terms {
            margin: 20px 0;
            padding: 15px;
            background-color: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .terms h3 {
            color: #2d3748;
            font-size: 13px;
            margin: 0 0 10px 0;
        }
        .terms table {
            width: 100%;
            border-collapse: collapse;
        }
        .terms td {
            padding: 5px 0;
            font-size: 11px;
        }
        .terms td:first-child {
            width: 40%;
            color: #4a5568;
            font-weight: bold;
        }
        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
        }
        .signature-line {
            border-top: 1px solid #1a202c;
            margin-top: 50px;
            padding-top: 5px;
            font-size: 10px;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            color: #a0aec0;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="letterhead">
        <h1>TNT Construction & Trading PLC</h1>
        <h2>Grade One General Contractor</h2>
        <p style="font-size:10px;color:#718096;">Addis Ababa, Ethiopia | hr@tnt-constructions.com</p>
    </div>

    <div class="reference">
        <p><strong>Ref:</strong> {{ $offer_reference_number ?? 'N/A' }}</p>
        <p><strong>Date:</strong> {{ now()->format('F d, Y') }}</p>
    </div>

    <p><strong>CONFIDENTIAL</strong></p>
    <p>Dear <strong>{{ $application->applicant->full_name_en ?? 'Candidate' }}</strong>,</p>
    <p><strong>SUBJECT: OFFER OF EMPLOYMENT</strong></p>
    <p>We are pleased to offer you employment with TNT Construction & Trading PLC on the following terms:</p>

    <div class="terms">
        <h3>TERMS AND CONDITIONS</h3>
        <table>
            <tr><td>Position:</td><td><strong>{{ $position_title ?? 'N/A' }}</strong></td></tr>
            <tr><td>Department:</td><td>{{ $department ?? 'N/A' }}</td></tr>
            <tr><td>Duty Station:</td><td>{{ $duty_station ?? 'N/A' }}</td></tr>
            <tr><td>Commencement Date:</td><td>{{ isset($reporting_date) ? \Carbon\Carbon::parse($reporting_date)->format('F d, Y') : 'N/A' }}</td></tr>
            <tr><td>Gross Monthly Salary:</td><td><strong>{{ isset($salary_amount) ? number_format($salary_amount, 2) : '0.00' }} {{ $salary_currency ?? 'ETB' }}</strong></td></tr>
            @if(!empty($benefits))
            <tr><td>Additional Benefits:</td><td>{{ $benefits }}</td></tr>
            @endif
            <tr><td>Probation Period:</td><td>3 Months</td></tr>
            <tr><td>Working Hours:</td><td>Monday - Friday, 8:00 AM - 5:00 PM</td></tr>
        </table>
    </div>

    <p>This offer is subject to satisfactory medical examination and document verification.</p>
    <p>Please sign and return this letter by <strong>{{ isset($offer_expiry_date) ? \Carbon\Carbon::parse($offer_expiry_date)->format('F d, Y') : 'N/A' }}</strong>.</p>
    <p>We look forward to welcoming you to the TNT Construction family.</p>

    <p style="margin-top:30px;">Sincerely,</p>
    <p style="margin-top:40px;"><strong>Human Resources Department</strong><br>TNT Construction & Trading PLC</p>

    <div class="signature-section">
        <div class="signature-box">
            <p><strong>For TNT Construction:</strong></p>
            <div class="signature-line">
                Name: _______________________<br>
                Title: HR Manager<br>
                Date: _______________________<br>
                Signature: _______________________
            </div>
        </div>
        <div class="signature-box">
            <p><strong>Acceptance by Employee:</strong></p>
            <div class="signature-line">
                I accept the offer as described above.<br>
                Signature: _______________________<br>
                Date: _______________________
            </div>
        </div>
    </div>

    <div class="footer">
        <p>TNT Construction & Trading PLC | Grade One General Contractor | Ref: {{ $offer_reference_number ?? 'N/A' }}</p>
    </div>
</body>
</html>
