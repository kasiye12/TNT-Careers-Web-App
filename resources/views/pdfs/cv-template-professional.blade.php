<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV - {{ $data['full_name'] ?? 'Candidate' }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 40px;
            color: #333;
            font-size: 11px;
            line-height: 1.4;
        }
        .center {
            text-align: center;
            margin-bottom: 20px;
        }
        .center h1 {
            font-size: 24px;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .center .contact {
            font-size: 10px;
            color: #666;
            margin-top: 8px;
        }
        h2 {
            font-size: 14px;
            text-transform: uppercase;
            border-bottom: 1px solid #333;
            padding-bottom: 3px;
            margin: 20px 0 10px;
        }
        .exp-item {
            margin-bottom: 10px;
        }
        .exp-item .header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
        }
        .edu-item {
            margin-bottom: 6px;
        }
    </style>
</head>
<body>
    <div class="center">
        <h1>{{ $data['full_name'] ?? '' }}</h1>
        <div class="contact">
            {{ $data['email'] ?? '' }} | {{ $data['phone'] ?? '' }}
            @if(!empty($data['address']))
                | {{ $data['address'] }}
            @endif
        </div>
    </div>

    @if(!empty($data['professional_summary']))
        <h2>Professional Summary</h2>
        <p>{{ $data['professional_summary'] }}</p>
    @endif

    @if(!empty($data['experience']) && is_array($data['experience']))
        <h2>Work Experience</h2>
        @foreach($data['experience'] as $exp)
            @if(!empty($exp['company']) || !empty($exp['position']))
                <div class="exp-item">
                    <div class="header">
                        <span>{{ $exp['position'] ?? '' }} - {{ $exp['company'] ?? '' }}</span>
                        <span>{{ $exp['from'] ?? '' }} - {{ $exp['to'] ?? 'Present' }}</span>
                    </div>
                    @if(!empty($exp['description']))
                        <p>{{ $exp['description'] }}</p>
                    @endif
                </div>
            @endif
        @endforeach
    @endif

    @if(!empty($data['education']) && is_array($data['education']))
        <h2>Education</h2>
        @foreach($data['education'] as $edu)
            @if(!empty($edu['institution']))
                <div class="edu-item">
                    <strong>{{ $edu['degree'] ?? '' }} in {{ $edu['field'] ?? '' }}</strong>
                    - {{ $edu['institution'] ?? '' }} ({{ $edu['year'] ?? '' }})
                </div>
            @endif
        @endforeach
    @endif

    @if(!empty($data['skills']))
        <h2>Skills</h2>
        <p>{{ $data['skills'] }}</p>
    @endif

    @if(!empty($data['languages']))
        <h2>Languages</h2>
        <p>{{ $data['languages'] }}</p>
    @endif
</body>
</html>
