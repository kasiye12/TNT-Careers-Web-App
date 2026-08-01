<!DOCTYPE html>
<html><head><meta charset="utf-8"><title>CV - {{ $data['full_name'] ?? '' }}</title>
<style>
    body { font-family: 'DejaVu Sans', sans-serif; margin: 0; color: #1a1a2e; font-size: 11px; line-height: 1.5; }
    .header { background: #0066ff; color: white; padding: 25px 35px; }
    .header h1 { margin: 0; font-size: 26px; font-weight: 700; }
    .header .contact { margin-top: 10px; font-size: 10px; display: flex; gap: 15px; flex-wrap: wrap; }
    .content { padding: 25px 35px; }
    .section { margin-bottom: 18px; }
    .section h2 { color: #0066ff; font-size: 13px; border-bottom: 2px solid #0066ff; padding-bottom: 4px; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px; }
    .exp-item { margin-bottom: 10px; padding-left: 12px; border-left: 3px solid #0066ff; }
    .skills { display: flex; flex-wrap: wrap; gap: 6px; }
    .skill-tag { background: #f0f4ff; color: #0066ff; padding: 3px 10px; border-radius: 15px; font-size: 10px; font-weight: 600; }
</style></head>
<body>
<div class="header"><h1>{{ $data['full_name'] ?? '' }}</h1><div class="contact"><span>📧 {{ $data['email'] ?? '' }}</span><span>📱 {{ $data['phone'] ?? '' }}</span>@if(!empty($data['address']))<span>📍 {{ $data['address'] }}</span>@endif</div></div>
<div class="content">
    @if(!empty($data['professional_summary']))<div class="section"><h2>Professional Summary</h2><p>{{ $data['professional_summary'] }}</p></div>@endif
    @if(!empty($data['experience']))<div class="section"><h2>Experience</h2>@foreach($data['experience'] as $exp)@if(!empty($exp['company']))<div class="exp-item"><strong>{{ $exp['position'] ?? '' }}</strong><br><span style="color:#0066ff">{{ $exp['company'] ?? '' }}</span> | {{ $exp['from'] ?? '' }} - {{ $exp['to'] ?? 'Present' }}<br>@if(!empty($exp['description']))<small>{{ $exp['description'] }}</small>@endif</div>@endif@endforeach</div>@endif
    @if(!empty($data['education']))<div class="section"><h2>Education</h2>@foreach($data['education'] as $edu)@if(!empty($edu['institution']))<p><strong>{{ $edu['degree'] ?? '' }} in {{ $edu['field'] ?? '' }}</strong> - {{ $edu['institution'] ?? '' }} ({{ $edu['year'] ?? '' }})</p>@endif@endforeach</div>@endif
    @if(!empty($data['skills']))<div class="section"><h2>Skills</h2><div class="skills">@foreach(explode(',', $data['skills']) as $skill)<span class="skill-tag">{{ trim($skill) }}</span>@endforeach</div></div>@endif
</div>
</body></html>
