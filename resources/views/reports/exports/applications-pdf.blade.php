<!DOCTYPE html><html><head><meta charset="utf-8"><title>Report</title>
<style>body{font-family:DejaVu Sans,sans-serif;font-size:11px}table{width:100%;border-collapse:collapse}th,td{border:1px solid #ddd;padding:6px;font-size:10px}th{background:#1a365d;color:#fff}</style></head>
<body>
<h2 style="text-align:center;color:#1a365d">TNT Construction & Trading PLC</h2>
<h3 style="text-align:center">Application Report - {{ $vacancy->title }}</h3>
<table><thead><tr><th>#</th><th>Name</th><th>Email</th><th>Phone</th><th>Gender</th><th>Exp</th><th>Status</th><th>Date</th></tr></thead>
<tbody>@foreach($applications as $i=>$a)<tr><td>{{$i+1}}</td><td>{{$a->applicant->full_name_en}}</td><td>{{$a->applicant->user->email}}</td><td>{{$a->applicant->user->phone}}</td><td>{{ucfirst($a->applicant->gender)}}</td><td>{{$a->applicant->total_years_exp}}y</td><td>{{ucwords(str_replace('_',' ',$a->status))}}</td><td>{{$a->created_at->format('Y-m-d')}}</td></tr>@endforeach</tbody></table>
</body></html>
