<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Shortlist Matrix</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { color: #1a365d; margin-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; font-size: 9px; }
        th { background-color: #2d3748; color: white; }
        .rank-1 { background-color: #f0fff4; }
        .high-score { color: #38a169; font-weight: bold; }
        .medium-score { color: #d69e2e; font-weight: bold; }
        .low-score { color: #e53e3e; font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>TNT Construction & Trading PLC</h1>
        <h2>Candidate Shortlist Matrix</h2>
        <p>Vacancy: {{ $vacancy->title }} ({{ $vacancy->vacancy_number }})</p>
        <p>Generated: {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Rank</th>
                <th>Candidate Name</th>
                <th>Experience</th>
                <th>Academic (30%)</th>
                <th>Written (40%)</th>
                <th>Interview (30%)</th>
                <th>Total Score</th>
            </tr>
        </thead>
        <tbody>
            @php $rank = 1; @endphp
            @foreach($applications as $application)
                <tr class="{{ $rank === 1 ? 'rank-1' : '' }}">
                    <td>{{ $rank }}</td>
                    <td>{{ $application->applicant->full_name_en }}</td>
                    <td>{{ $application->applicant->total_years_exp }} years</td>
                    <td>{{ number_format($application->academic_score, 1) }}%</td>
                    <td>{{ number_format($application->written_score, 1) }}%</td>
                    <td>{{ number_format($application->interview_score, 1) }}%</td>
                    <td class="@if($application->weighted_total >= 70) high-score @elseif($application->weighted_total >= 50) medium-score @else low-score @endif">
                        {{ number_format($application->weighted_total, 1) }}%
                    </td>
                </tr>
                @php $rank++; @endphp
            @endforeach
        </tbody>
    </table>
</body>
</html>
