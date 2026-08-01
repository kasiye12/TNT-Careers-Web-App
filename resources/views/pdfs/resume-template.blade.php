<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resume - {{ $data['full_name'] ?? 'Candidate' }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $fontFamily) }}:wght@300;400;500;600;700;800;900&display=swap');
        
        body {
            font-family: '{{ $fontFamily }}', sans-serif;
            font-size: {{ $fontSize == 'compact' ? '10px' : ($fontSize == 'large' ? '14px' : '12px') }};
            color: #0f172a;
            line-height: 1.6;
            margin: 35px 40px;
        }
        
        .header {
            text-align: center;
            margin-bottom: {{ $fontSize == 'compact' ? '16px' : ($fontSize == 'large' ? '24px' : '20px') }};
            padding-bottom: {{ $fontSize == 'compact' ? '12px' : ($fontSize == 'large' ? '20px' : '16px') }};
            border-bottom: 3px solid {{ $themeColor }};
        }
        .header h1 {
            font-size: {{ $fontSize == 'compact' ? '20px' : ($fontSize == 'large' ? '28px' : '24px') }};
            font-weight: 800;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: -0.5px;
        }
        .header .title {
            font-size: {{ $fontSize == 'compact' ? '12px' : ($fontSize == 'large' ? '18px' : '16px') }};
            color: {{ $themeColor }};
            font-weight: 600;
            margin: 4px 0 8px;
        }
        .header .contact {
            color: #64748b;
            font-size: {{ $fontSize == 'compact' ? '9px' : ($fontSize == 'large' ? '13px' : '11px') }};
        }
        
        h3 {
            color: {{ $themeColor }};
            font-size: {{ $fontSize == 'compact' ? '12px' : ($fontSize == 'large' ? '16px' : '14px') }};
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin: 16px 0 4px;
        }
        .divider {
            height: 2px;
            background: linear-gradient(to right, {{ $themeColor }}, transparent);
            margin-bottom: 10px;
        }
        
        .exp-item {
            margin-bottom: {{ $fontSize == 'compact' ? '8px' : ($fontSize == 'large' ? '16px' : '12px') }};
            padding-left: 12px;
            border-left: 3px solid {{ $themeColor }};
        }
        .exp-item strong { font-size: {{ $fontSize == 'compact' ? '11px' : ($fontSize == 'large' ? '15px' : '13px') }}; }
        .exp-item .company { color: {{ $themeColor }}; font-weight: 600; }
        .exp-item .date { color: #94a3b8; font-size: {{ $fontSize == 'compact' ? '9px' : ($fontSize == 'large' ? '13px' : '11px') }}; }
        .exp-item .desc { color: #475569; margin-top: 4px; }
        
        .skill-tag {
            display: inline-block;
            background: {{ $themeColor }}15;
            color: {{ $themeColor }};
            padding: 2px 12px;
            border-radius: 16px;
            font-weight: 600;
            margin: 2px 4px 2px 0;
            font-size: {{ $fontSize == 'compact' ? '8px' : ($fontSize == 'large' ? '12px' : '10px') }};
        }
        
        .skill-bar { background: #e5e7eb; height: 4px; border-radius: 4px; margin: 3px 0 8px; }
        .skill-bar-fill { background: {{ $themeColor }}; height: 4px; border-radius: 4px; }
        
        .lang-tag {
            display: inline-block;
            background: #f1f5f9;
            color: #475569;
            padding: 2px 12px;
            border-radius: 14px;
            margin: 2px 4px 2px 0;
            font-weight: 500;
            font-size: {{ $fontSize == 'compact' ? '8px' : ($fontSize == 'large' ? '12px' : '10px') }};
        }
        
        .cert-tag {
            display: inline-block;
            background: #fef2f2;
            color: #dc2626;
            padding: 2px 12px;
            border-radius: 14px;
            margin: 2px 4px 2px 0;
            font-weight: 500;
            font-size: {{ $fontSize == 'compact' ? '8px' : ($fontSize == 'large' ? '12px' : '10px') }};
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $data['full_name'] ?? 'Your Name' }}</h1>
        @if(!empty($data['job_title']))
            <div class="title">{{ $data['job_title'] }}</div>
        @endif
        <div class="contact">
            @php $contact = []; if(!empty($data['email'])) $contact[] = $data['email']; if(!empty($data['phone'])) $contact[] = $data['phone']; if(!empty($data['location'])) $contact[] = $data['location']; if(!empty($data['website'])) $contact[] = $data['website']; @endphp
            {{ implode('  •  ', $contact) }}
        </div>
    </div>

    @if(!empty($data['summary']))
        <h3>Professional Summary</h3>
        <div class="divider"></div>
        <p style="color:#475569;">{{ $data['summary'] }}</p>
    @endif

    @if(!empty($data['experience']) && is_array($data['experience']))
        <h3>Work Experience</h3>
        <div class="divider"></div>
        @foreach($data['experience'] as $exp)
            @if(!empty($exp['company']) || !empty($exp['position']))
                <div class="exp-item">
                    <div style="display:flex;justify-content:space-between;">
                        <strong>{{ $exp['position'] ?? 'Position' }}</strong>
                        <span class="date">{{ $exp['from'] ?? '' }} {{ !empty($exp['from'])&&!empty($exp['to'])?'—':'' }} {{ $exp['to'] ?? '' }}</span>
                    </div>
                    <div class="company">{{ $exp['company'] ?? 'Company' }}</div>
                    @if(!empty($exp['desc']))
                        <p class="desc">{{ $exp['desc'] }}</p>
                    @endif
                </div>
            @endif
        @endforeach
    @endif

    @if(!empty($data['education']) && is_array($data['education']))
        <h3>Education</h3>
        <div class="divider"></div>
        @foreach($data['education'] as $edu)
            @if(!empty($edu['school']) || !empty($edu['degree']))
                <p style="margin-bottom:6px;">
                    <strong>{{ $edu['degree'] ?? 'Degree' }}</strong>
                    @if(!empty($edu['gpa'])) <span style="color:#94a3b8;">| GPA: {{ $edu['gpa'] }}</span> @endif
                    <br><span style="color:#64748b;">{{ $edu['school'] ?? 'Institution' }} {{ !empty($edu['date'])?'('.$edu['date'].')':'' }}</span>
                </p>
            @endif
        @endforeach
    @endif

    @if(!empty($data['featured_skills']) && is_array($data['featured_skills']))
        <h3>Featured Skills</h3>
        <div class="divider"></div>
        @foreach($data['featured_skills'] as $skill)
            <div>
                <div style="display:flex;justify-content:space-between;font-size:{{ $fontSize == 'compact' ? '9px' : ($fontSize == 'large' ? '13px' : '11px') }};">
                    <span>{{ $skill['name'] }}</span><span style="color:#94a3b8;">{{ $skill['level'] }}/5</span>
                </div>
                <div class="skill-bar"><div class="skill-bar-fill" style="width:{{ ($skill['level']/5)*100 }}%;"></div></div>
            </div>
        @endforeach
    @endif

    @if(!empty($data['skills']))
        <h3>Skills</h3>
        <div class="divider"></div>
        <p>@foreach(explode(',', $data['skills']) as $skill) @if(trim($skill))<span class="skill-tag">{{ trim($skill) }}</span>@endif @endforeach</p>
    @endif

    @if(!empty($data['languages']))
        <h3>Languages</h3>
        <div class="divider"></div>
        <p>@foreach(explode("\n", $data['languages']) as $lang) @if(trim($lang))<span class="lang-tag">{{ trim($lang) }}</span>@endif @endforeach</p>
    @endif

    @if(!empty($data['certifications']))
        <h3>Certifications</h3>
        <div class="divider"></div>
        <p>@foreach(explode("\n", $data['certifications']) as $cert) @if(trim($cert))<span class="cert-tag">{{ trim($cert) }}</span>@endif @endforeach</p>
    @endif
</body>
</html>
