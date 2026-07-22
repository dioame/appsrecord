<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $creator->name }} — CV</title>
    <style>
        @page { margin: 32px 36px; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1d1d1f;
            line-height: 1.45;
        }
        .header {
            width: 100%;
            margin-bottom: 16px;
        }
        .header td {
            vertical-align: middle;
        }
        .avatar {
            width: 72px;
            height: 72px;
            border-radius: 36px;
            object-fit: cover;
        }
        .avatar-fallback {
            width: 72px;
            height: 72px;
            border-radius: 36px;
            background: #e8e8ed;
            color: #1d1d1f;
            text-align: center;
            line-height: 72px;
            font-size: 22px;
            font-weight: bold;
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
            margin: 0;
        }
        .bio {
            margin: 0 0 14px;
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
        .app {
            margin-bottom: 16px;
            page-break-inside: avoid;
            border: 1px solid #ececf0;
            border-radius: 10px;
            padding: 10px;
        }
        .app-top {
            width: 100%;
        }
        .app-top td {
            vertical-align: top;
        }
        .app-logo {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            object-fit: cover;
        }
        .app-logo-fallback {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            background: #e8e8ed;
            text-align: center;
            line-height: 48px;
            font-size: 11px;
            color: #86868b;
        }
        .app-name {
            font-size: 13px;
            font-weight: bold;
            margin: 0 0 2px;
        }
        .app-meta {
            font-size: 10px;
            color: #666;
            margin: 0 0 4px;
        }
        .app-desc {
            font-size: 10px;
            margin: 0;
            color: #333;
        }
        .shots {
            margin-top: 8px;
        }
        .shot {
            width: 150px;
            height: 90px;
            object-fit: cover;
            border-radius: 6px;
            margin-right: 6px;
            border: 1px solid #ececf0;
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
    <table class="header" cellpadding="0" cellspacing="0">
        <tr>
            <td width="84">
                @if ($avatarDataUri)
                    <img src="{{ $avatarDataUri }}" class="avatar" alt="">
                @else
                    <div class="avatar-fallback">{{ $creator->initials() }}</div>
                @endif
            </td>
            <td>
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
            </td>
        </tr>
    </table>

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

    @if ($apps->isNotEmpty())
        <h2>Deployed apps ({{ $apps->count() }})</h2>
        @foreach ($apps as $app)
            @php
                $logoDataUri = \App\Support\PdfImage::fromPublicDisk($app->logo);
                $shotDataUris = collect($app->images ?? [])
                    ->take(2)
                    ->map(fn (string $path) => \App\Support\PdfImage::fromPublicDisk($path))
                    ->filter()
                    ->values();
            @endphp
            <div class="app">
                <table class="app-top" cellpadding="0" cellspacing="0">
                    <tr>
                        <td width="58">
                            @if ($logoDataUri)
                                <img src="{{ $logoDataUri }}" class="app-logo" alt="">
                            @else
                                <div class="app-logo-fallback">App</div>
                            @endif
                        </td>
                        <td>
                            <p class="app-name">{{ $app->name }}</p>
                            <p class="app-meta">
                                {{ $app->platformLabel() }}
                                @if ($app->category)
                                    · {{ $app->category->name }}
                                @endif
                                @if ($app->link)
                                    · {{ \Illuminate\Support\Str::limit($app->link, 48) }}
                                @endif
                            </p>
                            @if ($app->description)
                                <p class="app-desc">{{ \Illuminate\Support\Str::limit($app->description, 180) }}</p>
                            @endif
                        </td>
                    </tr>
                </table>
                @if ($shotDataUris->isNotEmpty())
                    <div class="shots">
                        @foreach ($shotDataUris as $shot)
                            <img src="{{ $shot }}" class="shot" alt="">
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach
    @endif

    <div class="footer">
        Generated from {{ $creator->publicUrl() }}
    </div>
</body>
</html>
