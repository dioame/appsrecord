@extends('layouts.portfolio')

@section('title', $creator->name)
@section('meta_description', $creator->headline ?: ($creator->bio ?: 'Apps and CV by '.$creator->name))

@section('content')
@php
    $hasCv = $creator->hasCvContent();
    $skills = $creator->skillList();
    $experience = $creator->experienceEntries();
    $education = $creator->educationEntries();
@endphp
<div class="mx-auto w-full max-w-[1128px] px-0 py-0 sm:px-6 sm:py-6" x-data="{ cat: 'all' }">
    <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_300px]">

        {{-- ============ Main column ============ --}}
        <div class="min-w-0 space-y-2 sm:space-y-4">

            {{-- Profile card --}}
            <header class="li-card overflow-hidden">
                <div class="li-cover" aria-hidden="true"></div>
                <div class="relative px-5 pb-6 sm:px-6">
                    <div class="li-avatar author-avatar -mt-[68px] !h-[120px] !w-[120px] !text-[34px] sm:-mt-[84px] sm:!h-[152px] sm:!w-[152px] sm:!text-[42px]">
                        @if ($creator->avatarUrl())
                            <img src="{{ $creator->avatarUrl() }}" alt="{{ $creator->name }}" class="h-full w-full object-cover">
                        @else
                            <span>{{ $creator->initials() }}</span>
                        @endif
                    </div>

                    <div class="mt-3 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-x-2.5 gap-y-1">
                                <h1 class="font-display text-[24px] font-semibold leading-tight text-[#191919] sm:text-[26px]">{{ $creator->name }}</h1>
                                <x-trusted-badge :user="$creator" class="!text-[11px]" />
                            </div>
                            @if ($creator->headline)
                                <p class="mt-1 text-[16px] leading-snug text-[#191919]">{{ $creator->headline }}</p>
                            @endif
                            <p class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1 text-[14px] text-[#666666]">
                                @if ($creator->location)
                                    <span>{{ $creator->location }}</span>
                                    <span aria-hidden="true">·</span>
                                @endif
                                @if ($creator->websiteUrl())
                                    <a href="{{ $creator->websiteUrl() }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-[#0A66C2] hover:underline">{{ $creator->websiteHost() }}</a>
                                @else
                                    <span>Contact info unavailable</span>
                                @endif
                            </p>
                            <p class="mt-1.5 text-[14px] font-semibold text-[#0A66C2]">
                                {{ $apps->count() }} {{ \Illuminate\Support\Str::plural('app', $apps->count()) }}
                                @if ($categories->isNotEmpty())
                                    <span class="font-normal text-[#666666]">· {{ $categories->count() }} {{ \Illuminate\Support\Str::plural('category', $categories->count()) }}</span>
                                @endif
                            </p>
                        </div>

                        <div class="flex shrink-0 flex-wrap items-center gap-2">
                            @if ($hasCv)
                                <a href="{{ route('creators.cv.preview', $creator->slug) }}" class="li-btn li-btn-primary">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m0 0l-4-4m4 4l4-4M4 20h16"/></svg>
                                    Download CV
                                </a>
                            @endif
                            @if ($creator->websiteUrl())
                                <a href="{{ $creator->websiteUrl() }}" target="_blank" rel="noopener noreferrer" class="li-btn li-btn-outline">Visit website</a>
                            @endif
                            <a href="#about" class="li-btn li-btn-ghost">More</a>
                        </div>
                    </div>
                </div>
            </header>

            {{-- About --}}
            @if ($creator->bio)
                <section id="about" class="li-card li-section">
                    <h2 class="li-heading">About</h2>
                    <p class="mt-3 whitespace-pre-line text-[14px] leading-relaxed text-[#191919]">{{ $creator->bio }}</p>
                </section>
            @endif

            {{-- Apps / Projects --}}
            <section id="apps" class="li-card li-section">
                <div class="flex items-baseline justify-between gap-3">
                    <h2 class="li-heading">Apps &amp; projects</h2>
                    <span class="text-[13px] text-[#666666]">{{ $apps->count() }} total</span>
                </div>

                @if ($apps->isEmpty())
                    <p class="mt-4 text-[14px] text-[#666666]">No published apps yet.</p>
                @else
                    @if ($categories->count() > 1)
                        <div class="mt-4 flex flex-wrap gap-2" role="tablist" aria-label="Filter by category">
                            <button type="button" class="li-chip" :class="cat === 'all' ? 'li-chip-active' : ''" @click="cat = 'all'">All</button>
                            @foreach ($categories as $category)
                                <button type="button" class="li-chip" :class="cat === '{{ $category->slug ?? 'apps' }}' ? 'li-chip-active' : ''" @click="cat = '{{ $category->slug ?? 'apps' }}'">{{ $category->name }}</button>
                            @endforeach
                        </div>
                    @endif

                    <div class="mt-2">
                        @foreach ($categories as $category)
                            <div x-show="cat === 'all' || cat === '{{ $category->slug ?? 'apps' }}'" id="cat-{{ $category->slug ?? 'apps' }}">
                                @if ($categories->count() > 1)
                                    <p class="mt-5 text-[12px] font-semibold uppercase tracking-[0.06em] text-[#666666]" x-show="cat === 'all'">{{ $category->name }}</p>
                                @endif
                                <ul class="divide-y divide-[#E0DFDC]">
                                    @foreach ($category->apps as $app)
                                        <li>
                                            <a href="{{ route('creators.app', [$creator->slug, $app->slug]) }}" class="li-item group">
                                                <div class="li-app-icon h-12 w-12 sm:h-14 sm:w-14">
                                                    @if ($app->logoUrl())
                                                        <img src="{{ $app->logoUrl() }}" alt="" class="h-full w-full object-cover">
                                                    @else
                                                        <div class="flex h-full w-full items-center justify-center bg-[#EEF3F8] text-[#666666]">
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="3" stroke-width="1.5"/></svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <div class="flex min-w-0 flex-wrap items-center gap-x-2 gap-y-1">
                                                        <h3 class="truncate text-[15px] font-semibold text-[#191919] group-hover:text-[#0A66C2] group-hover:underline">{{ $app->name }}</h3>
                                                        <x-platform-badge :platform="$app->platform" />
                                                    </div>
                                                    <p class="mt-0.5 line-clamp-2 text-[14px] leading-snug text-[#666666]">{{ \Illuminate\Support\Str::limit($app->description, 110) }}</p>
                                                    <div class="mt-1.5">
                                                        <x-star-rating :rating="$app->averageRating()" :count="$app->ratingsCount()" />
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>

            {{-- Experience --}}
            @if (count($experience) > 0)
                <section id="experience" class="li-card li-section">
                    <h2 class="li-heading">Experience</h2>
                    <ul class="mt-4 space-y-5">
                        @foreach ($experience as $job)
                            <li class="flex gap-3.5">
                                <div class="li-logo-tile" aria-hidden="true">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div class="min-w-0 flex-1 border-b border-[#E0DFDC] pb-5 last:border-0 last:pb-0">
                                    <h3 class="text-[15px] font-semibold text-[#191919]">{{ $job['title'] ?? 'Role' }}</h3>
                                    @if (! empty($job['company']))
                                        <p class="text-[14px] text-[#191919]">{{ $job['company'] }}</p>
                                    @endif
                                    @if (! empty($job['period']))
                                        <p class="text-[13px] text-[#666666]">{{ $job['period'] }}</p>
                                    @endif
                                    @if (! empty($job['description']))
                                        <p class="mt-2 whitespace-pre-line text-[14px] leading-relaxed text-[#191919]">{{ $job['description'] }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            {{-- Education --}}
            @if (count($education) > 0)
                <section id="education" class="li-card li-section">
                    <h2 class="li-heading">Education</h2>
                    <ul class="mt-4 space-y-5">
                        @foreach ($education as $item)
                            <li class="flex gap-3.5">
                                <div class="li-logo-tile" aria-hidden="true">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                </div>
                                <div class="min-w-0 flex-1 border-b border-[#E0DFDC] pb-5 last:border-0 last:pb-0">
                                    <h3 class="text-[15px] font-semibold text-[#191919]">{{ $item['school'] ?? 'School' }}</h3>
                                    @if (! empty($item['degree']))
                                        <p class="text-[14px] text-[#191919]">{{ $item['degree'] }}</p>
                                    @endif
                                    @if (! empty($item['period']))
                                        <p class="text-[13px] text-[#666666]">{{ $item['period'] }}</p>
                                    @endif
                                    @if (! empty($item['description']))
                                        <p class="mt-2 whitespace-pre-line text-[14px] leading-relaxed text-[#191919]">{{ $item['description'] }}</p>
                                    @endif
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            {{-- Skills --}}
            @if (count($skills) > 0)
                <section id="skills" class="li-card li-section">
                    <h2 class="li-heading">Skills</h2>
                    <ul class="mt-3 divide-y divide-[#E0DFDC]">
                        @foreach ($skills as $skill)
                            <li class="py-3 text-[15px] font-semibold text-[#191919] first:pt-0 last:pb-0">{{ $skill }}</li>
                        @endforeach
                    </ul>
                </section>
            @endif
        </div>

        {{-- ============ Sidebar ============ --}}
        <aside class="space-y-2 sm:space-y-4">
            <section class="li-card li-section">
                <h2 class="text-[16px] font-semibold text-[#191919]">Profile</h2>
                <dl class="mt-3 space-y-3 text-[14px]">
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-[#666666]">Published apps</dt>
                        <dd class="font-semibold tabular-nums text-[#191919]">{{ $apps->count() }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-[#666666]">Categories</dt>
                        <dd class="font-semibold tabular-nums text-[#191919]">{{ $categories->count() }}</dd>
                    </div>
                    <div class="flex items-center justify-between gap-3">
                        <dt class="text-[#666666]">Skills</dt>
                        <dd class="font-semibold tabular-nums text-[#191919]">{{ count($skills) }}</dd>
                    </div>
                </dl>
            </section>

            @if ($hasCv)
                <section class="li-card li-section">
                    <h2 class="text-[16px] font-semibold text-[#191919]">Curriculum vitae</h2>
                    <p class="mt-1 text-[13px] text-[#666666]">Preview a template and download as PDF.</p>
                    <a href="{{ route('creators.cv.preview', $creator->slug) }}" class="li-btn li-btn-primary mt-3 w-full">Preview & download</a>
                </section>
            @endif

            @if ($creator->websiteUrl())
                <section class="li-card li-section">
                    <h2 class="text-[16px] font-semibold text-[#191919]">Website</h2>
                    <a href="{{ $creator->websiteUrl() }}" target="_blank" rel="noopener noreferrer" class="li-btn li-btn-outline mt-3 w-full">{{ $creator->websiteHost() }}</a>
                </section>
            @endif

            @if (count($skills) > 0)
                <section class="li-card li-section">
                    <h2 class="text-[16px] font-semibold text-[#191919]">Top skills</h2>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach (array_slice($skills, 0, 8) as $skill)
                            <span class="li-chip cursor-default">{{ $skill }}</span>
                        @endforeach
                    </div>
                </section>
            @endif
        </aside>
    </div>
</div>
@endsection
