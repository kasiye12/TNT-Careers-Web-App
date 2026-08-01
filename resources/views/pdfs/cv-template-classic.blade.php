<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CV - {{ $data['full_name'] ?? 'Classic' }}</title>
    <style>
        body {
            font-family: 'DejaVu Serif', serif;
            margin: 50px;
            color: #2c3e50;
            font-size: 11px;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            border-bottom: 3px double #2c3e50;
            padding-bottom: 20px;
            margin-bottom: 25px;
        }
        .header h1 {
            font-size: 28px;
            margin: 0;
            font-weight: normal;
        }
        .header p {
            font-size: 10px;
            color: #7f8c8d;
            margin: 5px 0;
        }
        h2 {
            font-size: 13px;
            font-variant: small-caps;
            letter-spacing: 2px;
            color: #2c3e50;
            margin: 18px 0 8px;
        }
        .item {
            margin-bottom: 10px;
            padding-left: 10px;
            border-left: 2px solid #bdc3c7;
        }
        .item strong {
            font-size: 11px;
        }
        .item .meta {
            font-size: 10px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $data['full_name'] ?? '' }}</h1>
        <p>{{ $data['email'] ?? '' }} | {{ $data['phone'] ?? '' }}
        @if(!empty($data['address']))
            | {{ $data['address'] }}
        @endif</p>
    </div>

    @if(!empty($data['professional_summary']))
        <h2>Profile</h2>
        <p>{{ $data['professional_summary'] }}</p>
    @endif

    @if(!empty($data['experience']) && is_array($data['experience']))
        <h2>Experience</h2>
        @foreach($data['experience'] as $exp)
            @if(!empty($exp['company']) || !empty($exp['position']))
                <div class="item">
                    <strong>{{ $exp['position'] ?? 'Position' }}</strong><br>
                    <span class="meta">{{ $exp['company'] ?? '' }} | {{ $exp['from'] ?? '' }} - {{ $exp['to'] ?? 'Present' }}</span>
                    @if(!empty($exp['description']))
                        <br>{{ $exp['description'] }}
                    @endif
                </div>
            @endif
        @endforeach
    @endif

    @if(!empty($data['education']) && is_array($data['education']))
        <h2>Education</h2>
        @foreach($data['education'] as $edu)
            @if(!empty($edu['institution']))
                <div class="item">
                    <strong>{{ $edu['degree'] ?? '' }} in {{ $edu['field'] ?? '' }}</strong><br>
                    <span class="meta">{{ $edu['institution'] ?? '' }} ({{ $edu['year'] ?? '' }})</span>
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
