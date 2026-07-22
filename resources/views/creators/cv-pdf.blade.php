<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $creator->name }} — CV</title>
    <style>
        @page { margin: 36px 40px; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1d1d1f;
            line-height: 1.45;
        }
        h1 {
            font-size: 22px;
            margin: 0 0 4px;
            font-weight: bold;
        }
        .headline {
            font-size: 13px;
            margin: 0 0 6px;
            color: #333;
        }
        .meta {
            font-size: 10px;
            color: #666;
            margin: 0 0 14px;
        }
        .bio {
            margin: 0 0 18px;
            font-size: 11px;
        }
        h2 {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #555;
            border-bottom: 1px solid #ddd;
            padding-bottom: 4px;
            margin: 18px 0 10px;
        }
        .skills {
            margin: 0;
            padding: 0;
        }
        .skill {
            display: inline-block;
            background: #f0f0f2;
            padding: 3px 8px;
            margin: 0 4px 6px 0;
            border-radius: 10px;
            font-size: 10px;
        }
        .entry {
            margin-bottom: 12px;
        }
        .entry-head {
            overflow: hidden;
        }
        .entry-title {
            font-weight: bold;
            font-size: 12px;
            float: left;
            width: 70%;
        }
        .entry-period {
            float: right;
            width: 28%;
            text-align: right;
            color: #666;
            font-size: 10px;
        }
        .entry-sub {
            clear: both;
            color: #555;
            margin: 2px 0 4px;
            font-size: 10px;
        }
        .entry-desc {
            clear: both;
            margin: 0;
        }
        .footer {
            margin-top: 28px;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <h1>{{ $creator->name }}</h1>

    @if ($creator->headline)
        <p class="headline">{{ $creator->headline }}</p>
    @endif

    @if ($creator->location || $creator->websiteHost())
        <p class="meta">
            @if ($creator->location){{ $creator->location }}@endif
            @if ($creator->location && $creator->websiteHost()) · @endif
            @if ($creator->websiteHost()){{ $creator->websiteHost() }}@endif
        </p>
    @endif

    @if ($creator->bio)
        <p class="bio">{{ $creator->bio }}</p>
    @endif

    @if (count($creator->skillList()) > 0)
        <h2>Skills</h2>
        <div class="skills">
            @foreach ($creator->skillList() as $skill)
                <span class="skill">{{ $skill }}</span>
            @endforeach
        </div>
    @endif

    @if (count($creator->experienceEntries()) > 0)
        <h2>Experience</h2>
        @foreach ($creator->experienceEntries() as $job)
            <div class="entry">
                <div class="entry-head">
                    <div class="entry-title">{{ $job['title'] ?? 'Role' }}</div>
                    @if (! empty($job['period']))
                        <div class="entry-period">{{ $job['period'] }}</div>
                    @endif
                </div>
                @if (! empty($job['company']))
                    <div class="entry-sub">{{ $job['company'] }}</div>
                @endif
                @if (! empty($job['description']))
                    <p class="entry-desc">{{ $job['description'] }}</p>
                @endif
            </div>
        @endforeach
    @endif

    @if (count($creator->educationEntries()) > 0)
        <h2>Education</h2>
        @foreach ($creator->educationEntries() as $item)
            <div class="entry">
                <div class="entry-head">
                    <div class="entry-title">{{ $item['school'] ?? 'School' }}</div>
                    @if (! empty($item['period']))
                        <div class="entry-period">{{ $item['period'] }}</div>
                    @endif
                </div>
                @if (! empty($item['degree']))
                    <div class="entry-sub">{{ $item['degree'] }}</div>
                @endif
                @if (! empty($item['description']))
                    <p class="entry-desc">{{ $item['description'] }}</p>
                @endif
            </div>
        @endforeach
    @endif

    <div class="footer">
        Generated from {{ $creator->publicUrl() }}
    </div>
</body>
</html>
