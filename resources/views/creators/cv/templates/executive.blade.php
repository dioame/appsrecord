{{-- Executive: navy sidebar professional --}}
@php
    $avatar = $media['avatar'] ?? null;
@endphp
<style>
    .cv-exec { font-family: DejaVu Sans, Helvetica, Arial, sans-serif; color: #020617; font-size: 10.5px; line-height: 1.45; }
    .cv-exec .frame { width: 100%; border-collapse: collapse; }
    .cv-exec .sidebar { width: 32%; background: #0F172A; color: #F8FAFC; padding: 16px 12px; vertical-align: top; }
    .cv-exec .main { width: 68%; background: #F8FAFC; padding: 16px 14px; vertical-align: top; }
    .cv-exec .photo { width: 88px; height: 88px; border-radius: 44px; display: block; margin: 0 auto 10px; border: 2px solid #334155; }
    .cv-exec .photo-fallback { width: 88px; height: 88px; border-radius: 44px; background: #334155; text-align: center; line-height: 88px; font-size: 24px; font-weight: bold; margin: 0 auto 10px; color: #F8FAFC; }
    .cv-exec .side-name { text-align: center; font-size: 16px; font-weight: bold; margin: 0 0 4px; color: #F8FAFC; }
    .cv-exec .side-role { text-align: center; font-size: 10px; color: #94A3B8; margin: 0 0 12px; }
    .cv-exec .side-h { font-size: 9px; text-transform: uppercase; letter-spacing: 0.1em; color: #38BDF8; margin: 14px 0 6px; border-bottom: 1px solid #334155; padding-bottom: 3px; }
    .cv-exec .side-p { color: #CBD5E1; font-size: 9.5px; margin: 0 0 4px; }
    .cv-exec .chip { display: inline-block; background: #1E293B; color: #E2E8F0; padding: 2px 6px; margin: 0 3px 4px 0; border-radius: 8px; font-size: 8.5px; }
    .cv-exec h1 { font-size: 18px; margin: 0 0 2px; color: #0F172A; }
    .cv-exec .lead { color: #475569; margin: 0 0 10px; font-size: 11px; }
    .cv-exec h2 { font-size: 11px; text-transform: uppercase; letter-spacing: 0.08em; color: #0369A1; border-bottom: 1px solid #E2E8F0; padding-bottom: 3px; margin: 12px 0 8px; }
    .cv-exec .entry { margin-bottom: 9px; }
    .cv-exec .entry-title { font-weight: bold; font-size: 11px; }
    .cv-exec .entry-period { float: right; color: #64748B; font-size: 9px; }
    .cv-exec .entry-sub { clear: both; color: #475569; font-size: 9.5px; margin: 1px 0 3px; }
    .cv-exec .app { margin-bottom: 10px; page-break-inside: avoid; background: #FFFFFF; border: 1px solid #E2E8F0; padding: 8px; }
    .cv-exec .app-logo { width: 40px; height: 40px; }
    .cv-exec .app-logo-fallback { width: 40px; height: 40px; background: #E2E8F0; text-align: center; line-height: 40px; font-size: 8px; color: #64748B; }
    .cv-exec .app-name { font-weight: bold; font-size: 11px; margin: 0 0 2px; }
    .cv-exec .app-meta { font-size: 8.5px; color: #64748B; margin: 0 0 3px; }
    .cv-exec .shot { width: 120px; height: 72px; margin: 5px 5px 0 0; border: 1px solid #E2E8F0; }
    .cv-exec .footer { margin-top: 12px; font-size: 8px; color: #94A3B8; }
</style>

<div class="cv-exec">
    <table class="frame" cellpadding="0" cellspacing="0">
        <tr>
            <td class="sidebar">
                @if ($avatar)
                    <img src="{{ $avatar }}" class="photo" width="88" height="88" alt="">
                @else
                    <div class="photo-fallback">{{ $creator->initials() }}</div>
                @endif
                <p class="side-name">{{ $creator->name }}</p>
                @if ($creator->headline)<p class="side-role">{{ $creator->headline }}</p>@endif

                <p class="side-h">Contact</p>
                @if ($creator->location)<p class="side-p">{{ $creator->location }}</p>@endif
                @if ($creator->websiteHost())<p class="side-p">{{ $creator->websiteHost() }}</p>@endif

                @if (count($creator->skillList()) > 0)
                    <p class="side-h">Skills</p>
                    <div>
                        @foreach ($creator->skillList() as $skill)
                            <span class="chip">{{ $skill }}</span>
                        @endforeach
                    </div>
                @endif

                @if (count($creator->educationEntries()) > 0)
                    <p class="side-h">Education</p>
                    @foreach ($creator->educationEntries() as $item)
                        <p class="side-p" style="font-weight:bold;color:#F8FAFC;margin-bottom:1px;">{{ $item['school'] ?? 'School' }}</p>
                        @if (! empty($item['degree']))<p class="side-p">{{ $item['degree'] }}</p>@endif
                        @if (! empty($item['period']))<p class="side-p" style="margin-bottom:8px;">{{ $item['period'] }}</p>@endif
                    @endforeach
                @endif
            </td>
            <td class="main">
                @if ($creator->bio)
                    <h1>Profile</h1>
                    <p class="lead">{{ $creator->bio }}</p>
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

                @if ($apps->isNotEmpty())
                    <h2>Deployed apps ({{ $apps->count() }})</h2>
                    @foreach ($apps as $app)
                        @php
                            $logo = $media['apps'][$app->id]['logo'] ?? null;
                            $shots = $media['apps'][$app->id]['shots'] ?? [];
                        @endphp
                        <div class="app">
                            <table cellpadding="0" cellspacing="0" width="100%">
                                <tr>
                                    <td width="48" valign="top">
                                        @if ($logo)
                                            <img src="{{ $logo }}" class="app-logo" width="40" height="40" alt="">
                                        @else
                                            <div class="app-logo-fallback">App</div>
                                        @endif
                                    </td>
                                    <td valign="top">
                                        <p class="app-name">{{ $app->name }}</p>
                                        <p class="app-meta">
                                            {{ $app->platformLabel() }}
                                            @if ($app->category) · {{ $app->category->name }}@endif
                                        </p>
                                        @if ($app->description)
                                            <p>{{ \Illuminate\Support\Str::limit($app->description, 140) }}</p>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            @if (count($shots) > 0)
                                <div>
                                    @foreach ($shots as $shot)
                                        <img src="{{ $shot }}" class="shot" width="120" height="72" alt="">
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                @endif

                <div class="footer">Generated from {{ $creator->publicUrl() }} · Executive template</div>
            </td>
        </tr>
    </table>
</div>
