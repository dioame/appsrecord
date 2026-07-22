{{-- Classic: clean single-column CV --}}
@php
    $avatar = $media['avatar'] ?? null;
@endphp
<style>
    .cv-classic { font-family: DejaVu Sans, Helvetica, Arial, sans-serif; color: #09090B; font-size: 11px; line-height: 1.45; }
    .cv-classic .hero { width: 100%; margin-bottom: 14px; border-bottom: 2px solid #18181B; padding-bottom: 12px; }
    .cv-classic .hero td { vertical-align: middle; }
    .cv-classic .photo { width: 78px; height: 78px; border-radius: 39px; }
    .cv-classic .photo-fallback { width: 78px; height: 78px; border-radius: 39px; background: #E4E4E7; text-align: center; line-height: 78px; font-size: 22px; font-weight: bold; color: #18181B; }
    .cv-classic h1 { font-size: 24px; margin: 0 0 4px; font-weight: bold; color: #18181B; }
    .cv-classic .headline { font-size: 13px; margin: 0 0 4px; color: #3F3F46; }
    .cv-classic .meta { font-size: 10px; color: #71717A; margin: 0; }
    .cv-classic .bio { margin: 0 0 12px; }
    .cv-classic h2 { font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; color: #2563EB; border-bottom: 1px solid #E4E4E7; padding-bottom: 4px; margin: 16px 0 8px; }
    .cv-classic .skill { display: inline-block; background: #F4F4F5; padding: 3px 8px; margin: 0 4px 5px 0; border-radius: 10px; font-size: 10px; }
    .cv-classic .entry { margin-bottom: 10px; }
    .cv-classic .entry-title { font-weight: bold; font-size: 12px; }
    .cv-classic .entry-period { float: right; color: #71717A; font-size: 10px; }
    .cv-classic .entry-sub { color: #52525B; font-size: 10px; margin: 2px 0 3px; clear: both; }
    .cv-classic .app { margin-bottom: 10px; page-break-inside: avoid; border-bottom: 1px solid #E4E4E7; padding-bottom: 8px; }
    .cv-classic .app-name { font-size: 12px; font-weight: bold; margin: 0 0 2px; }
    .cv-classic .app-meta { font-size: 9px; color: #71717A; margin: 0 0 3px; }
    .cv-classic .footer { margin-top: 18px; font-size: 8px; color: #A1A1AA; border-top: 1px solid #E4E4E7; padding-top: 6px; }
</style>

<div class="cv-classic">
    <table class="hero" cellpadding="0" cellspacing="0">
        <tr>
            <td width="90">
                @if ($avatar)
                    <img src="{{ $avatar }}" class="photo" width="78" height="78" alt="">
                @else
                    <div class="photo-fallback">{{ $creator->initials() }}</div>
                @endif
            </td>
            <td>
                <h1>{{ $creator->name }}</h1>
                @if ($creator->headline)<p class="headline">{{ $creator->headline }}</p>@endif
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

    @if ($creator->bio)<p class="bio">{{ $creator->bio }}</p>@endif

    @if (count($creator->skillList()) > 0)
        <h2>Skills</h2>
        <div>
            @foreach ($creator->skillList() as $skill)
                <span class="skill">{{ $skill }}</span>
            @endforeach
        </div>
    @endif

    @if (count($creator->experienceEntries()) > 0)
        <h2>Experience</h2>
        @foreach ($creator->experienceEntries() as $job)
            <div class="entry">
                @if (! empty($job['period']))<span class="entry-period">{{ $job['period'] }}</span>@endif
                <div class="entry-title">{{ $job['title'] ?? 'Role' }}</div>
                @if (! empty($job['company']))<div class="entry-sub">{{ $job['company'] }}</div>@endif
                @if (! empty($job['description']))<p>{{ $job['description'] }}</p>@endif
            </div>
        @endforeach
    @endif

    @if (count($creator->educationEntries()) > 0)
        <h2>Education</h2>
        @foreach ($creator->educationEntries() as $item)
            <div class="entry">
                @if (! empty($item['period']))<span class="entry-period">{{ $item['period'] }}</span>@endif
                <div class="entry-title">{{ $item['school'] ?? 'School' }}</div>
                @if (! empty($item['degree']))<div class="entry-sub">{{ $item['degree'] }}</div>@endif
                @if (! empty($item['description']))<p>{{ $item['description'] }}</p>@endif
            </div>
        @endforeach
    @endif

    @if ($apps->isNotEmpty())
        <h2>Deployed apps ({{ $apps->count() }})</h2>
        @foreach ($apps as $app)
            <div class="app">
                <p class="app-name">{{ $app->name }}</p>
                <p class="app-meta">
                    {{ $app->platformLabel() }}
                    @if ($app->category) · {{ $app->category->name }}@endif
                    @if ($app->link) · {{ \Illuminate\Support\Str::limit($app->link, 42) }}@endif
                </p>
                @if ($app->description)
                    <p>{{ \Illuminate\Support\Str::limit($app->description, 160) }}</p>
                @endif
            </div>
        @endforeach
    @endif

    <div class="footer">Generated from {{ $creator->publicUrl() }} · Classic template</div>
</div>
