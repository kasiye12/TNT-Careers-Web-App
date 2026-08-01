<table>
    <thead>
        <tr><th colspan="8" style="text-align:center;font-size:16px;font-weight:bold;">TNT Construction - Application Report</th></tr>
        <tr><th colspan="8" style="text-align:center;">Vacancy: {{ $vacancy->title }} ({{ $vacancy->vacancy_number }})</th></tr>
        <tr>
            <th>No.</th><th>Applicant</th><th>Email</th><th>Phone</th><th>Gender</th><th>Experience</th><th>Status</th><th>Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach($applications as $index => $app)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $app->applicant->full_name_en }}</td>
                <td>{{ $app->applicant->user->email }}</td>
                <td>{{ $app->applicant->user->phone }}</td>
                <td>{{ ucfirst($app->applicant->gender) }}</td>
                <td>{{ $app->applicant->total_years_exp }} yrs</td>
                <td>{{ ucwords(str_replace('_', ' ', $app->status)) }}</td>
                <td>{{ $app->created_at->format('Y-m-d') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
