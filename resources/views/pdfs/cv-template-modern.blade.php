<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV - {{ $data['full_name'] ?? 'Candidate' }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            color: #1a1a2e;
            font-size: 11px;
            line-height: 1.5;
        }
        .header {
            background: #0066ff;
            color: white;
            padding: 25px 35px;
        }
        .header h1 {
            margin: 0;
            font-size: 26px;
            font-weight: 700;
        }
        .header .contact {
            margin-top: 10px;
            font-size: 10px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }
        .content {
            padding: 25px 35px;
        }
        .section {
            margin-bottom: 18px;
        }
        .section h2 {
            color: #0066ff;
            font-size: 13px;
            font-weight: 700;
            border-bottom: 2px solid #0066ff;
            padding-bottom: 4px;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .summary {
            background: #f0f4ff;
            padding: 12px;
            border-radius: 6px;
            font-size: 10px;
        }
        .exp-item {
            margin-bottom: 10px;
            padding-left: 12px;
            border-left: 3px solid #0066ff;
        }
        .exp-item h4 {
            margin: 0;
            font-size: 12px;
        }
        .exp-item .company {
            color: #0066ff;
            font-size: 11px;
            font-weight: 600;
        }
        .exp-item .date {
            color: #888;
            font-size: 10px;
        }
        .exp-item p {
            font-size: 10px;
            color: #555;
            margin: 4px 0 0;
        }
        .skills {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        .skill-tag {
            background: #f0f4ff;
            color: #0066ff;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 10px;
            font-weight: 600;
        }
        .edu-item {
            margin-bottom: 6px;
        }
        .edu-item strong {
            font-size: 11px;
        }
        .edu-item span {
            color: #888;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $data['full_name'] ?? '' }}</h1>
        <div class="contact">
            <span>📧 {{ $data['email'] ?? '' }}</span>
            <span>📱 {{ $data['phone'] ?? '' }}</span>
            @if(!empty($data['address']))
                <span>📍 {{ $data['address'] }}</span>
            @endif
        </div>
    </div>

    <div class="content">
        @if(!empty($data['professional_summary']))
            <div class="section">
                <h2>Professional Summary</h2>
                <div class="summary">{{ $data['professional_summary'] }}</div>
            </div>
        @endif

        @if(!empty($data['experience']) && is_array($data['experience']))
            <div class="section">
                <h2>Work Experience</h2>
                @foreach($data['experience'] as $exp)
                    @if(!empty($exp['company']) || !empty($exp['position']))
                        <div class="exp-item">
                            <h4>{{ $exp['position'] ?? 'Position' }}</h4>
                            <div class="company">{{ $exp['company'] ?? '' }}</div>
                            <div class="date">{{ $exp['from'] ?? '' }} - {{ $exp['to'] ?? 'Present' }}</div>
                            @if(!empty($exp['description']))
                                <p>{{ $exp['description'] }}</p>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        @if(!empty($data['education']) && is_array($data['education']))
            <div class="section">
                <h2>Education</h2>
                @foreach($data['education'] as $edu)
                    @if(!empty($edu['institution']))
                        <div class="edu-item">
                            <strong>{{ $edu['degree'] ?? '' }} in {{ $edu['field'] ?? '' }}</strong><br>
                            {{ $edu['institution'] ?? '' }}
                            <span>({{ $edu['year'] ?? '' }})</span>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif

        @if(!empty($data['skills']))
            <div class="section">
                <h2>Skills</h2>
                <div class="skills">
                    @php
                        $skillsArray = explode(',', $data['skills']);
                    @endphp
                    @foreach($skillsArray as $skill)
                        @if(trim($skill))
                            <span class="skill-tag">{{ trim($skill) }}</span>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        @if(!empty($data['languages']))
            <div class="section">
                <h2>Languages</h2>
                <p style="font-size:10px">{{ $data['languages'] }}</p>
            </div>
        @endif
    </div>
</body>
</html>
