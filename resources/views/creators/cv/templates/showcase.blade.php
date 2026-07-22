{{-- Showcase: portfolio-first with large screenshots --}}
@php
    $avatar = $media['avatar'] ?? null;
@endphp
<style>
    .cv-show { font-family: DejaVu Sans, Helvetica, Arial, sans-serif; color: #134E4A; font-size: 11px; line-height: 1.45; }
    .cv-show .banner { background: #0F766E; color: #F0FDFA; padding: 16px 14px; margin-bottom: 14px; }
    .cv-show .banner table { width: 100%; }
    .cv-show .banner td { vertical-align: middle; }
    .cv-show .photo { width: 70px; height: 70px; border-radius: 12px; border: 2px solid #5EEAD4; }
    .cv-show .photo-fallback { width: 70px; height: 70px; border-radius: 12px; background: #115E59; text-align: center; line-height: 70px; font-size: 20px; font-weight: bold; color: #F0FDFA; }
    .cv-show h1 { font-size: 22px; margin: 0 0 3px; color: #F0FDFA; }
    .cv-show .headline { margin: 0 0 3px; color: #99F6E4; font-size: 12px; }
    .cv-show .meta { margin: 0; color: #CCFBF1; font-size: 10px; }
    .cv-show .pad { padding: 0 2px; }
    .cv-show .bio { margin: 0 0 12px; color: #134E4A; }
    .cv-show h2 { font-size: 12px; text-transform: uppercase; letter-spacing: 0.08em; color: #0F766E; border-bottom: 2px solid #99F6E4; padding-bottom: 4px; margin: 14px 0 8px; }
    .cv-show .skill { display: inline-block; background: #CCFBF1; color: #115E59; padding: 3px 8px; margin: 0 4px 5px 0; border-radius: 4px; font-size: 10px; }
    .cv-show .entry { margin-bottom: 10px; }
    .cv-show .entry-title { font-weight: bold; font-size: 12px; color: #134E4A; }
    .cv-show .entry-period { float: right; color: #0F766E; font-size: 10px; }
    .cv-show .entry-sub { clear: both; color: #0F766E; font-size: 10px; margin: 2px 0 3px; }
    .cv-show .app { margin-bottom: 14px; page-break-inside: avoid; border: 1px solid #99F6E4; background: #F0FDFA; padding: 10px; }
    .cv-show .app-head { margin-bottom: 6px; }
    .cv-show .app-logo { width: 36px; height: 36px; vertical-align: middle; margin-right: 8px; }
    .cv-show .app-logo-fallback { display: inline-block; width: 36px; height: 36px; background: #99F6E4; text-align: center; line-height: 36px; font-size: 8px; color: #115E59; vertical-align: middle; margin-right: 8px; }
    .cv-show .app-name { font-size: 13px; font-weight: bold; color: #134E4A; display: inline; }
    .cv-show .app-meta { font-size: 9px; color: #0F766E; margin: 4px 0; }
    .cv-show .shot { width: 165px; height: 100px; margin: 4px 6px 0 0; border: 1px solid #5EEAD4; }
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
            @foreach ($apps as $app)
                @php
                    $logo = $media['apps'][$app->id]['logo'] ?? null;
                    $shots = $media['apps'][$app->id]['shots'] ?? [];
                @endphp
                <div class="app">
                    <div class="app-head">
                        @if ($logo)
                            <img src="{{ $logo }}" class="app-logo" width="36" height="36" alt="">
                        @else
                            <span class="app-logo-fallback">App</span>
                        @endif
                        <span class="app-name">{{ $app->name }}</span>
                    </div>
                    <p class="app-meta">
                        {{ $app->platformLabel() }}
                        @if ($app->category) · {{ $app->category->name }}@endif
                        @if ($app->link) · {{ \Illuminate\Support\Str::limit($app->link, 40) }}@endif
                    </p>
                    @if ($app->description)
                        <p>{{ \Illuminate\Support\Str::limit($app->description, 150) }}</p>
                    @endif
                    @if (count($shots) > 0)
                        <div>
                            @foreach ($shots as $shot)
                                <img src="{{ $shot }}" class="shot" width="165" height="100" alt="">
                            @endforeach
                        </div>
                    @elseif ($logo)
                        {{-- Always show logo at larger size when no screenshots --}}
                        <div>
                            <img src="{{ $logo }}" class="shot" width="100" height="100" alt="">
                        </div>
                    @endif
                </div>
            @endforeach
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

        <div class="footer">Generated from {{ $creator->publicUrl() }} · Showcase template</div>
    </div>
</div>
