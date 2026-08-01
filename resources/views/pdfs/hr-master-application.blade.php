<!DOCTYPE html><html><head><meta charset="utf-8"><title>HR Master</title>
<style>body{font-family:DejaVu Sans,sans-serif;font-size:11px}table{width:100%;border-collapse:collapse;margin:10px 0}th,td{border:1px solid #ddd;padding:6px;font-size:10px}th{background:#1a365d;color:#fff}h2{color:#1a365d;margin:0}</style></head>
<body>
<h2>TNT Construction - HR Master Application</h2>
<p>Generated: {{ now()->format('Y-m-d H:i') }}</p>
<p><strong>Applicant:</strong> {{ $applicant->full_name_en }} | {{ $applicant->user->email }} | {{ $applicant->user->phone }}</p>
<p><strong>Position:</strong> {{ $vacancy->title }} ({{ $vacancy->vacancy_number }})</p>

<h3>Work Experience</h3>
<table><tr><th>Organization</th><th>Position</th><th>Project</th><th>Duration</th></tr>
@foreach($workExperiences as $exp)
<tr><td>{{$exp->organization_name}}</td><td>{{$exp->position_held}}</td><td>{{$exp->project_type??'N/A'}}</td><td>{{$exp->from_date->format('M Y')}} - {{$exp->is_current?'Present':($exp->to_date?$exp->to_date->format('M Y'):'N/A')}}</td></tr>
@endforeach</table>

<h3>Education</h3>
<table><tr><th>Institution</th><th>Qualification</th><th>Field</th><th>Year</th></tr>
@foreach($educationHistories as $edu)
<tr><td>{{$edu->institution}}</td><td>{{$edu->qualification_label}}</td><td>{{$edu->field_of_study}}</td><td>{{$edu->graduation_year}}</td></tr>
@endforeach</table>
</body></html>
