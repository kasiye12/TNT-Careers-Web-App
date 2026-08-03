<table>
    <thead>
        <tr>
            <th colspan="10" style="text-align:center;font-size:16px;font-weight:bold;background:#0b3b5a;color:white;padding:10px;">
                TNT Construction & Trading PLC - Applications Report
            </th>
        </tr>
        <tr>
            <th colspan="10" style="text-align:center;padding:5px;">
                Generated: {{ now()->format('F d, Y H:i') }} | Total: {{ $applications->count() }}
            </th>
        </tr>
        <tr style="background:#e5e7eb;">
            <th style="border:1px solid #ccc;padding:8px;">No.</th>
            <th style="border:1px solid #ccc;padding:8px;">Applicant Name</th>
            <th style="border:1px solid #ccc;padding:8px;">Email</th>
            <th style="border:1px solid #ccc;padding:8px;">Phone</th>
            <th style="border:1px solid #ccc;padding:8px;">Position Applied</th>
            <th style="border:1px solid #ccc;padding:8px;">Department</th>
            <th style="border:1px solid #ccc;padding:8px;">Experience</th>
            <th style="border:1px solid #ccc;padding:8px;">Status</th>
            <th style="border:1px solid #ccc;padding:8px;">Applied Date</th>
            <th style="border:1px solid #ccc;padding:8px;">Gender</th>
        </tr>
    </thead>
    <tbody>
        @foreach($applications as $index => $app)
            <tr>
                <td style="border:1px solid #ccc;padding:6px;text-align:center;">{{ $index + 1 }}</td>
                <td style="border:1px solid #ccc;padding:6px;">{{ $app->applicant->full_name_en ?? 'N/A' }}</td>
                <td style="border:1px solid #ccc;padding:6px;">{{ $app->applicant->user->email ?? '' }}</td>
                <td style="border:1px solid #ccc;padding:6px;">{{ $app->applicant->user->phone ?? '' }}</td>
                <td style="border:1px solid #ccc;padding:6px;">{{ $app->vacancy->title ?? 'N/A' }}</td>
                <td style="border:1px solid #ccc;padding:6px;">{{ $app->vacancy->department ?? '' }}</td>
                <td style="border:1px solid #ccc;padding:6px;text-align:center;">{{ $app->applicant->total_years_exp ?? 0 }} yrs</td>
                <td style="border:1px solid #ccc;padding:6px;text-align:center;">{{ ucwords(str_replace('_', ' ', $app->status)) }}</td>
                <td style="border:1px solid #ccc;padding:6px;text-align:center;">{{ $app->created_at->format('Y-m-d') }}</td>
                <td style="border:1px solid #ccc;padding:6px;text-align:center;">{{ ucfirst($app->applicant->gender ?? 'N/A') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
