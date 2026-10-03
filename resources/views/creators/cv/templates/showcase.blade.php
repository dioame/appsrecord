{{-- Showcase: portfolio-first apps list (text only, no app pictures) --}}
@php
    $avatar = $media['avatar'] ?? null;
@endphp
<style>
    .cv-show { font-family: DejaVu Sans, Helvetica, Arial, sans-serif; color: #163C3A; font-size: 11px; line-height: 1.55; }
    .cv-show p { margin-top: 0; }
    .cv-show .banner { background: #0F766E; color: #F0FDFA; padding: 18px 17px; margin-bottom: 18px; }
    .cv-show .banner table { width: 100%; }
    .cv-show .banner td { vertical-align: middle; }
    .cv-show .photo { width: 70px; height: 70px; border-radius: 12px; border: 2px solid #5EEAD4; }
    .cv-show .photo-fallback { width: 70px; height: 70px; border-radius: 12px; background: #115E59; text-align: center; line-height: 70px; font-size: 20px; font-weight: bold; color: #F0FDFA; }
    .cv-show h1 { font-size: 24px; margin: 0 0 4px; color: #F0FDFA; letter-spacing: -0.01em; }
    .cv-show .headline { margin: 0 0 3px; color: #99F6E4; font-size: 12px; }
    .cv-show .meta { margin: 0; color: #CCFBF1; font-size: 10px; }
    .cv-show .pad { padding: 0 2px; }
    .cv-show .bio { margin: 0 0 16px; color: #285E5A; }
    .cv-show h2 { font-size: 11px; text-transform: uppercase; letter-spacing: 0.11em; color: #0F766E; border-bottom: 2px solid #99F6E4; padding-bottom: 6px; margin: 20px 0 11px; }
    .cv-show .skill { display: inline-block; background: #CCFBF1; color: #115E59; padding: 3px 8px; margin: 0 4px 5px 0; border-radius: 4px; font-size: 10px; }
    .cv-show .entry { margin-bottom: 10px; }
    .cv-show .entry-title { font-weight: bold; font-size: 12px; color: #134E4A; }
    .cv-show .entry-period { float: right; color: #0F766E; font-size: 10px; }
    .cv-show .entry-sub { clear: both; color: #0F766E; font-size: 10px; margin: 2px 0 3px; }
    .cv-show .app-grid { width: 100%; border-collapse: separate; border-spacing: 0 9px; margin-top: -9px; }
    .cv-show .app-cell { width: 50%; vertical-align: top; padding-right: 6px; page-break-inside: avoid; }
    .cv-show .app-cell + .app-cell { padding-right: 0; padding-left: 6px; }
    .cv-show .app { min-height: 66px; page-break-inside: avoid; background: #F0FDFA; border-top: 3px solid #14B8A6; padding: 9px 10px 10px; }
    .cv-show .app-name { font-size: 11px; font-weight: bold; color: #134E4A; margin: 0 0 3px; }
    .cv-show .app-meta { font-size: 8px; color: #0F766E; margin: 0 0 4px; }
    .cv-show .app-copy { font-size: 9.5px; line-height: 1.45; margin: 0; color: #285E5A; }
    .cv-show .footer { margin-top: 16px; font-size: 8px; color: #5EEAD4; border-top: 1px solid #99F6E4; padding-top: 6px; }
</style>

<div class="cv-show">
    <div class="banner">
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td width="84">
                    @if ($avatar)
                        <img src="{{ $avatar }}" class="photo" width="70" height="70" alt="">
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
    </div>

    <div class="pad">
        @if ($creator->bio)<p class="bio">{{ $creator->bio }}</p>@endif

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

        <div class="footer">Portfolio: {{ $creator->publicUrl() }}</div>
    </div>
</div>
