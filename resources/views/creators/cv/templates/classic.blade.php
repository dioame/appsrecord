{{-- Classic: clean single-column CV --}}
@php
    $avatar = $media['avatar'] ?? null;
@endphp
<style>
    .cv-classic { font-family: DejaVu Sans, Helvetica, Arial, sans-serif; color: #172033; font-size: 11px; line-height: 1.55; }
    .cv-classic p { margin-top: 0; }
    .cv-classic .hero { width: 100%; margin-bottom: 20px; border-bottom: 3px solid #1E3A5F; padding-bottom: 16px; }
    .cv-classic .hero td { vertical-align: middle; }
    .cv-classic .photo { width: 78px; height: 78px; border-radius: 39px; }
    .cv-classic .photo-fallback { width: 78px; height: 78px; border-radius: 39px; background: #E4E4E7; text-align: center; line-height: 78px; font-size: 22px; font-weight: bold; color: #18181B; }
    .cv-classic h1 { font-size: 27px; margin: 0 0 5px; font-weight: bold; color: #0F172A; letter-spacing: -0.02em; }
    .cv-classic .headline { font-size: 13px; margin: 0 0 6px; color: #334155; font-weight: bold; }
    .cv-classic .meta { font-size: 10px; color: #64748B; margin: 0; }
    .cv-classic .bio { margin: 0 0 16px; color: #334155; }
    .cv-classic h2 { font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.14em; color: #1E3A5F; border-bottom: 1px solid #CBD5E1; padding-bottom: 6px; margin: 22px 0 12px; }
    .cv-classic .skill { display: inline-block; background: #F1F5F9; border: 1px solid #DCE4ED; padding: 4px 9px; margin: 0 5px 6px 0; border-radius: 4px; font-size: 9.5px; color: #0F172A; }
    .cv-classic .entry { margin-bottom: 13px; }
    .cv-classic .entry-title { font-weight: bold; font-size: 12px; }
    .cv-classic .entry-period { float: right; color: #71717A; font-size: 10px; }
    .cv-classic .entry-sub { color: #1E3A5F; font-size: 10.5px; font-weight: bold; margin: 2px 0 3px; clear: both; }
    .cv-classic .app-grid { width: 100%; border-collapse: separate; border-spacing: 0 8px; margin-top: -8px; }
    .cv-classic .app-cell { width: 50%; vertical-align: top; padding-right: 6px; page-break-inside: avoid; }
    .cv-classic .app-cell + .app-cell { padding-right: 0; padding-left: 6px; }
    .cv-classic .app { min-height: 62px; page-break-inside: avoid; background: #F8FAFC; border: 1px solid #E2E8F0; border-left: 3px solid #1E3A5F; padding: 9px 10px; }
    .cv-classic .app-name { font-size: 11px; font-weight: bold; margin: 0 0 3px; color: #0F172A; }
    .cv-classic .app-meta { font-size: 8px; color: #64748B; margin: 0 0 4px; }
    .cv-classic .app-copy { font-size: 9.5px; line-height: 1.45; margin: 0; color: #334155; }
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
        <table class="app-grid" cellpadding="0" cellspacing="0">
            @foreach ($apps->chunk(2) as $row)
                <tr>
                    @foreach ($row as $app)
                        <td class="app-cell">
                            <div class="app">
                                <p class="app-name">{{ $app->name }}</p>
                                <p class="app-meta">
                                    {{ $app->platformLabel() }}
                                    @if ($app->category) · {{ $app->category->name }}@endif
                                    @if ($app->link) · {{ \Illuminate\Support\Str::limit($app->link, 34) }}@endif
                                </p>
                                @if ($app->description)
                                    <p class="app-copy">{{ \Illuminate\Support\Str::limit($app->description, 115) }}</p>
                                @endif
                            </div>
                        </td>
                    @endforeach
                    @if ($row->count() === 1)<td class="app-cell"></td>@endif
                </tr>
            @endforeach
        </table>
    @endif

    <div class="footer">Portfolio: {{ $creator->publicUrl() }}</div>
</div>
