{{-- Executive: navy sidebar professional --}}
@php
    $avatar = $media['avatar'] ?? null;
@endphp
<style>
    .cv-exec { font-family: DejaVu Sans, Helvetica, Arial, sans-serif; color: #172033; font-size: 10.5px; line-height: 1.55; }
    .cv-exec p { margin-top: 0; }
    .cv-exec .frame { width: 100%; border-collapse: collapse; }
    .cv-exec .sidebar { width: 31%; background: #0F172A; color: #F8FAFC; padding: 22px 16px; vertical-align: top; }
    .cv-exec .main { width: 69%; background: #F8FAFC; padding: 22px 20px; vertical-align: top; }
    .cv-exec .photo { width: 88px; height: 88px; border-radius: 44px; display: block; margin: 0 auto 10px; border: 2px solid #334155; }
    .cv-exec .photo-fallback { width: 88px; height: 88px; border-radius: 44px; background: #334155; text-align: center; line-height: 88px; font-size: 24px; font-weight: bold; margin: 0 auto 10px; color: #F8FAFC; }
    .cv-exec .side-name { text-align: center; font-size: 17px; font-weight: bold; margin: 0 0 5px; color: #F8FAFC; }
    .cv-exec .side-role { text-align: center; font-size: 10px; line-height: 1.45; color: #CBD5E1; margin: 0 0 17px; }
    .cv-exec .side-h { font-size: 9px; text-transform: uppercase; letter-spacing: 0.12em; color: #7DD3FC; margin: 18px 0 8px; border-bottom: 1px solid #334155; padding-bottom: 5px; }
    .cv-exec .side-p { color: #D7E0EB; font-size: 9.5px; line-height: 1.5; margin: 0 0 5px; }
    .cv-exec .chip { display: inline-block; background: #1E293B; border: 1px solid #334155; color: #F1F5F9; padding: 3px 7px; margin: 0 4px 5px 0; border-radius: 8px; font-size: 8.5px; }
    .cv-exec h1 { font-size: 20px; margin: 0 0 5px; color: #0F172A; }
    .cv-exec .lead { color: #475569; margin: 0 0 15px; font-size: 10.5px; line-height: 1.6; }
    .cv-exec h2 { font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.1em; color: #0369A1; border-bottom: 2px solid #BAE6FD; padding-bottom: 5px; margin: 17px 0 10px; }
    .cv-exec .entry { margin-bottom: 12px; }
    .cv-exec .entry-title { font-weight: bold; font-size: 11px; }
    .cv-exec .entry-period { float: right; color: #64748B; font-size: 9px; }
    .cv-exec .entry-sub { clear: both; color: #475569; font-size: 9.5px; margin: 1px 0 3px; }
    .cv-exec .app { margin-bottom: 10px; page-break-inside: avoid; background: #FFFFFF; border: 1px solid #E2E8F0; border-left: 3px solid #0EA5E9; padding: 8px 9px; }
    .cv-exec .app-name { font-weight: bold; font-size: 10.5px; margin: 0 0 3px; color: #0F172A; }
    .cv-exec .app-meta { font-size: 8px; color: #64748B; margin: 0 0 4px; }
    .cv-exec .app-copy { font-size: 9.5px; line-height: 1.45; margin: 0; color: #334155; }
    .cv-exec .footer { margin-top: 16px; padding-top: 6px; border-top: 1px solid #CBD5E1; font-size: 8px; color: #64748B; }
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
                        <div class="app">
                            <p class="app-name">{{ $app->name }}</p>
                            <p class="app-meta">
                                {{ $app->platformLabel() }}
                                @if ($app->category) · {{ $app->category->name }}@endif
                                @if ($app->link) · {{ \Illuminate\Support\Str::limit($app->link, 40) }}@endif
                            </p>
                            @if ($app->description)
                                <p class="app-copy">{{ \Illuminate\Support\Str::limit($app->description, 120) }}</p>
                            @endif
                        </div>
                    @endforeach
                @endif

                <div class="footer">Portfolio: {{ $creator->publicUrl() }}</div>
            </td>
        </tr>
    </table>
</div>
