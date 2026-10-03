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
<div class="mx-auto w-full max-w-[760px] px-4 pb-16 pt-4 sm:px-6 sm:pt-6" x-data="{ cat: 'all' }">

    {{-- Hero --}}
    <header>
        <div class="ios-cover">
            <a href="{{ route('home') }}" class="ios-back">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                AppsRecord
            </a>
            <a href="{{ route('home') }}" class="ios-cover-brand">apps.rendovations.com</a>
        </div>

        <div class="relative px-1">
            <div class="ios-avatar author-avatar -mt-[52px] ml-3 !h-[104px] !w-[104px] !text-[30px] sm:-mt-[60px] sm:ml-5 sm:!h-[120px] sm:!w-[120px] sm:!text-[34px]">
                @if ($creator->avatarUrl())
                    <img src="{{ $creator->avatarUrl() }}" alt="{{ $creator->name }}" class="h-full w-full object-cover">
                @else
                    <span>{{ $creator->initials() }}</span>
                @endif
            </div>

            <div class="mt-4 px-2 sm:px-3">
                <div class="flex flex-wrap items-center gap-x-2.5 gap-y-1">
                    <h1 class="font-display text-[30px] font-bold leading-tight tracking-tight text-[#1C1C1E] sm:text-[36px]">{{ $creator->name }}</h1>
                    <x-trusted-badge :user="$creator" class="!text-[11px]" />
                </div>
                @if ($creator->headline)
                    <p class="mt-1 text-[17px] text-[#3C3C43]/80">{{ $creator->headline }}</p>
                @endif
                @if ($creator->location)
                    <p class="mt-1.5 flex items-center gap-1.5 text-[15px] text-[#8E8E93]">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 2a7 7 0 00-7 7c0 5 7 13 7 13s7-8 7-13a7 7 0 00-7-7zm0 9.5A2.5 2.5 0 119.5 9 2.5 2.5 0 0112 11.5z"/></svg>
                        {{ $creator->location }}
                    </p>
                @endif

                <div class="mt-5 flex flex-wrap gap-2.5">
                    @if ($hasCv)
                        <a href="{{ route('creators.cv.preview', $creator->slug) }}" class="ios-btn ios-btn-filled">
                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v12m0 0l-4-4m4 4l4-4M5 20h14"/></svg>
                            Download CV
                        </a>
                    @endif
                    @if ($creator->websiteUrl())
                        <a href="{{ $creator->websiteUrl() }}" target="_blank" rel="noopener noreferrer" class="ios-btn ios-btn-tinted">
                            <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path stroke-linecap="round" d="M3 12h18M12 3c2.5 2.7 3.8 5.7 3.8 9s-1.3 6.3-3.8 9c-2.5-2.7-3.8-5.7-3.8-9S9.5 5.7 12 3z"/></svg>
                            {{ $creator->websiteHost() }}
                        </a>
                    @endif
                    <a href="{{ route('home') }}" class="ios-btn ios-btn-tinted">
                        <svg class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="4" width="7" height="7" rx="2"/><rect x="13" y="4" width="7" height="7" rx="2"/><rect x="4" y="13" width="7" height="7" rx="2"/><rect x="13" y="13" width="7" height="7" rx="2"/></svg>
                        Browse AppsRecord
                    </a>
                </div>
            </div>
        </div>
    </header>

    {{-- Stat widgets --}}
    <div class="mt-6 grid grid-cols-3 gap-3">
        <div class="ios-widget">
            <p class="ios-widget-num text-[#0A84FF]">{{ $apps->count() }}</p>
            <p class="ios-widget-label">{{ \Illuminate\Support\Str::plural('App', $apps->count()) }}</p>
        </div>
        <div class="ios-widget">
            <p class="ios-widget-num text-[#FF9F0A]">{{ $categories->count() }}</p>
            <p class="ios-widget-label">{{ \Illuminate\Support\Str::plural('Category', $categories->count()) }}</p>
        </div>
        <div class="ios-widget">
            <p class="ios-widget-num text-[#30D158]">{{ count($skills) }}</p>
            <p class="ios-widget-label">{{ \Illuminate\Support\Str::plural('Skill', count($skills)) }}</p>
        </div>
    </div>

    {{-- About --}}
    @if ($creator->bio)
        <section id="about" class="mt-8">
            <h2 class="ios-header">About</h2>
            <div class="ios-group px-5 py-4">
                <p class="whitespace-pre-line text-[16px] leading-relaxed text-[#1C1C1E]">{{ $creator->bio }}</p>
            </div>
        </section>
    @endif

    {{-- Apps --}}
    <section id="apps" class="mt-8">
        <h2 class="ios-header">Apps</h2>

        @if ($apps->isEmpty())
            <div class="ios-group px-5 py-8 text-center text-[15px] text-[#8E8E93]">No published apps yet.</div>
        @else
            @if ($categories->count() > 1)
                <div class="-mx-4 mb-3 flex gap-2 overflow-x-auto px-4 pb-1 sm:mx-0 sm:flex-wrap sm:px-0" role="tablist" aria-label="Filter by category">
                    <button type="button" class="ios-pill" :class="cat === 'all' ? 'ios-pill-active' : ''" @click="cat = 'all'">All</button>
                    @foreach ($categories as $category)
                        <button type="button" class="ios-pill" :class="cat === '{{ $category->slug ?? 'apps' }}' ? 'ios-pill-active' : ''" @click="cat = '{{ $category->slug ?? 'apps' }}'">{{ $category->name }}</button>
                    @endforeach
                </div>
            @endif

            <div class="space-y-5">
                @foreach ($categories as $category)
                    <div x-show="cat === 'all' || cat === '{{ $category->slug ?? 'apps' }}'" id="cat-{{ $category->slug ?? 'apps' }}">
                        @if ($categories->count() > 1)
                            <p class="mb-1.5 px-4 text-[13px] font-medium uppercase tracking-wide text-[#8E8E93]" x-show="cat === 'all'">{{ $category->name }}</p>
                        @endif
                        <ul class="ios-group">
                            @foreach ($category->apps as $app)
                                <li>
                                    <a href="{{ route('creators.app', [$creator->slug, $app->slug]) }}" class="ios-row group">
                                        <div class="app-icon h-[56px] w-[56px] sm:h-[64px] sm:w-[64px]">
                                            @if ($app->logoUrl())
                                                <img src="{{ $app->logoUrl() }}" alt="" class="h-full w-full object-cover">
                                            @else
                                                <div class="flex h-full w-full items-center justify-center bg-[#E5E5EA] text-[#8E8E93]">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4" stroke-width="1.5"/></svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ios-row-body min-w-0 flex-1">
                                            <div class="flex min-w-0 items-center gap-2">
                                                <h3 class="truncate text-[17px] font-semibold leading-tight text-[#1C1C1E]">{{ $app->name }}</h3>
                                                <x-platform-badge :platform="$app->platform" />
                                            </div>
                                            <p class="mt-0.5 line-clamp-2 text-[14px] leading-snug text-[#8E8E93]">{{ \Illuminate\Support\Str::limit($app->description, 110) }}</p>
                                            <div class="mt-1.5">
                                                <x-star-rating :rating="$app->averageRating()" :count="$app->ratingsCount()" />
                                            </div>
                                        </div>
                                        <svg class="ios-chevron" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
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
        <section id="experience" class="mt-8">
            <h2 class="ios-header">Experience</h2>
            <ul class="ios-group">
                @foreach ($experience as $job)
                    <li class="ios-row !items-start">
                        <div class="ios-glyph bg-[#FF9F0A]" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <div class="ios-row-body min-w-0 flex-1">
                            <div class="flex flex-wrap items-baseline justify-between gap-x-3">
                                <h3 class="text-[17px] font-semibold text-[#1C1C1E]">{{ $job['title'] ?? 'Role' }}</h3>
                                @if (! empty($job['period']))
                                    <span class="text-[13px] text-[#8E8E93]">{{ $job['period'] }}</span>
                                @endif
                            </div>
                            @if (! empty($job['company']))
                                <p class="text-[15px] text-[#0A84FF]">{{ $job['company'] }}</p>
                            @endif
                            @if (! empty($job['description']))
                                <p class="mt-1.5 whitespace-pre-line text-[15px] leading-relaxed text-[#3C3C43]/85">{{ $job['description'] }}</p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    {{-- Education --}}
    @if (count($education) > 0)
        <section id="education" class="mt-8">
            <h2 class="ios-header">Education</h2>
            <ul class="ios-group">
                @foreach ($education as $item)
                    <li class="ios-row !items-start">
                        <div class="ios-glyph bg-[#30D158]" aria-hidden="true">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                        </div>
                        <div class="ios-row-body min-w-0 flex-1">
                            <div class="flex flex-wrap items-baseline justify-between gap-x-3">
                                <h3 class="text-[17px] font-semibold text-[#1C1C1E]">{{ $item['school'] ?? 'School' }}</h3>
                                @if (! empty($item['period']))
                                    <span class="text-[13px] text-[#8E8E93]">{{ $item['period'] }}</span>
                                @endif
                            </div>
                            @if (! empty($item['degree']))
                                <p class="text-[15px] text-[#0A84FF]">{{ $item['degree'] }}</p>
                            @endif
                            @if (! empty($item['description']))
                                <p class="mt-1.5 whitespace-pre-line text-[15px] leading-relaxed text-[#3C3C43]/85">{{ $item['description'] }}</p>
                            @endif
                        </div>
                    </li>
                @endforeach
            </ul>
        </section>
    @endif

    {{-- Skills --}}
    @if (count($skills) > 0)
        <section id="skills" class="mt-8">
            <h2 class="ios-header">Skills</h2>
            <div class="ios-group flex flex-wrap gap-2 px-4 py-4">
                @foreach ($skills as $skill)
                    <span class="ios-tag">{{ $skill }}</span>
                @endforeach
            </div>
        </section>
    @endif

    {{-- CV --}}
    @if ($hasCv)
        <section id="cv" class="mt-8">
            <h2 class="ios-header">Curriculum vitae</h2>
            <a href="{{ route('creators.cv.preview', $creator->slug) }}" class="ios-group ios-row group">
                <div class="ios-glyph bg-[#0A84FF]" aria-hidden="true">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-[17px] font-semibold text-[#1C1C1E]">Preview & download</p>
                    <p class="text-[14px] text-[#8E8E93]">Choose a template and save as PDF</p>
                </div>
                <svg class="ios-chevron" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
            </a>
        </section>
    @endif

    <p class="mt-10 text-center text-[13px] text-[#8E8E93]">
        <a href="{{ route('home') }}" class="hover:underline">apps.rendovations.com</a>
    </p>
</div>
@endsection
