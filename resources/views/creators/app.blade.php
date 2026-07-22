@extends('layouts.portfolio')

@section('title', $app->name.' — '.$creator->name)
@section('meta_description', \Illuminate\Support\Str::limit($app->description, 155))

@section('content')
@php
    $shots = $app->imageUrls();
    $tagline = \Illuminate\Support\Str::limit($app->description, 70);
    $captions = [
        'Discover what makes '.$app->name.' special.',
        'Built for everyday use — simple and focused.',
        'See how '.$app->name.' fits into your routine.',
    ];
@endphp

<article class="mx-auto w-full max-w-[980px] px-4 py-6 sm:px-6 sm:py-8">
    <a href="{{ route('creators.show', $creator->slug) }}" class="mb-4 inline-flex items-center gap-1.5 text-[13px] font-medium text-[#0071E3] hover:opacity-70">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
        {{ $creator->name }}
    </a>

    <section class="hero-banner reveal">
        <div class="relative flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:gap-8 sm:p-8">
            <div class="app-icon h-[96px] w-[96px] ring-1 ring-white/25 sm:h-[132px] sm:w-[132px]">
                @if ($app->logoUrl())
                    <img src="{{ $app->logoUrl() }}" alt="{{ $app->name }} logo" class="h-full w-full object-cover">
                @endif
            </div>

            <div class="min-w-0 flex-1">
                <h1 class="font-display text-[28px] font-bold leading-tight tracking-tight sm:text-[40px] sm:leading-none">{{ $app->name }}</h1>
                <p class="mt-2 max-w-xl text-[15px] leading-snug text-white/90 sm:text-[17px]">{{ $tagline }}</p>
                <p class="mt-2 text-[13px] text-white/70 sm:text-[14px]">
                    Free · {{ $app->platformLabel() }} · {{ $app->category->name }}
                </p>
                <div class="mt-3">
                    <x-star-rating :rating="$app->averageRating()" :count="$app->ratingsCount()" size="md" light />
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2 sm:self-start">
                @if ($app->link)
                    <a href="{{ $app->link }}" target="_blank" rel="noopener noreferrer" class="inline-flex cursor-pointer items-center gap-2 rounded-full bg-white px-5 py-2 text-[14px] font-bold text-[#0071E3] transition hover:bg-white/90">
                        {{ $app->isWeb() ? 'Open' : 'Get' }}
                    </a>
                @endif
            </div>
        </div>
    </section>

    <section class="mt-1 grid grid-cols-2 divide-x divide-[#D2D2D7] border-y border-[#D2D2D7] bg-white {{ $app->link ? 'sm:grid-cols-4' : 'sm:grid-cols-3' }}">
        <div class="stat-cell">
            <p class="stat-label">Category</p>
            <svg class="mt-1 h-6 w-6 text-[#86868B]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h10M4 17h14"/></svg>
            <p class="stat-sub truncate max-w-full">{{ $app->category->name }}</p>
        </div>
        <div class="stat-cell">
            <p class="stat-label">Developer</p>
            <svg class="mt-1 h-6 w-6 text-[#86868B]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 10-16 0M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
            <p class="stat-sub truncate max-w-full">
                <a href="{{ route('creators.show', $creator->slug) }}" class="text-[#0071E3] hover:underline">{{ $app->authorName() }}</a>
            </p>
        </div>
        <div class="stat-cell">
            <p class="stat-label">Platform</p>
            <svg class="mt-1 h-6 w-6 text-[#86868B]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><rect x="5" y="3" width="14" height="18" rx="2"/><path stroke-linecap="round" d="M12 17h.01"/></svg>
            <p class="stat-sub truncate max-w-full">{{ $app->platformLabel() }}</p>
        </div>
        @if ($app->link)
            <div class="stat-cell">
                <p class="stat-label">URL</p>
                <svg class="mt-1 h-6 w-6 text-[#86868B]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                <a href="{{ $app->link }}" target="_blank" rel="noopener noreferrer" class="stat-sub mt-1 truncate max-w-full text-[#0071E3] hover:underline">
                    {{ \Illuminate\Support\Str::of($app->link)->replaceMatches('#^https?://#', '')->trim('/') }}
                </a>
            </div>
        @endif
    </section>

    <div class="bg-white px-0 sm:px-0">
        @include('partials.app-rating', ['app' => $app, 'userRating' => $userRating ?? null])
    </div>

    <section
        class="mt-8"
        x-data="{
            openIndex: null,
            images: {{ \Illuminate\Support\Js::from(array_values($shots)) }},
            captions: {{ \Illuminate\Support\Js::from(collect(array_values($shots))->keys()->map(fn ($i) => $captions[$i] ?? 'Screenshot '.($i + 1))->values()->all()) }},
            get isOpen() { return this.openIndex !== null },
            openAt(i) { this.openIndex = i },
            close() { this.openIndex = null },
            prev() { if (!this.images.length) return; this.openIndex = (this.openIndex - 1 + this.images.length) % this.images.length },
            next() { if (!this.images.length) return; this.openIndex = (this.openIndex + 1) % this.images.length },
        }"
        @keydown.escape.window="close()"
        @keydown.left.window="if (isOpen) prev()"
        @keydown.right.window="if (isOpen) next()"
    >
        @if (count($shots))
            <div class="store-shelf !gap-5">
                @foreach (array_values($shots) as $index => $shot)
                    @include('partials.device-preview', [
                        'app' => $app,
                        'shot' => $shot,
                        'index' => $index,
                        'caption' => $captions[$index] ?? 'A closer look at '.$app->name.'.',
                    ])
                @endforeach
            </div>

            <div
                x-show="isOpen"
                x-cloak
                x-transition.opacity
                class="fixed inset-0 z-[80] flex items-center justify-center bg-black/80 p-3 backdrop-blur-sm sm:p-4"
                @click.self="close()"
                role="dialog"
                aria-modal="true"
                aria-label="Screenshot preview"
            >
                <button type="button" class="absolute right-3 top-4 inline-flex h-10 w-10 cursor-pointer items-center justify-center rounded-full bg-white/15 text-white" @click="close()" aria-label="Close">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                @if (count($shots) > 1)
                    <button type="button" class="absolute left-2 top-1/2 inline-flex h-10 w-10 -translate-y-1/2 cursor-pointer items-center justify-center rounded-full bg-white/15 text-white sm:left-6" @click="prev()" aria-label="Previous">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button type="button" class="absolute right-2 top-1/2 inline-flex h-10 w-10 -translate-y-1/2 cursor-pointer items-center justify-center rounded-full bg-white/15 text-white sm:right-6" @click="next()" aria-label="Next">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                @endif
                <div class="flex max-h-[85vh] w-full max-w-5xl flex-col items-center gap-2" @click.stop>
                    <p class="px-8 text-center text-[13px] font-medium text-white/80" x-text="captions[openIndex] || ''"></p>
                    <img :src="images[openIndex]" :alt="'Screenshot ' + (openIndex + 1)" class="max-h-[70vh] w-auto max-w-full rounded-xl object-contain shadow-2xl sm:max-h-[80vh]">
                    <p class="text-[12px] text-white/50" x-text="(openIndex + 1) + ' / ' + images.length"></p>
                </div>
            </div>
        @endif
    </section>

    <section id="about" class="mt-10 max-w-3xl">
        <h2 class="section-title">About</h2>
        <div class="mt-3 whitespace-pre-line text-[15px] leading-relaxed text-[#1D1D1F]">{{ $app->description }}</div>
    </section>

    @if ($related->isNotEmpty())
        <section class="mt-10">
            <div class="mb-3 flex items-baseline justify-between">
                <h2 class="section-title">More from {{ $creator->name }}</h2>
            </div>
            <div class="rounded-[22px] bg-white px-3 sm:px-4">
                @foreach ($related as $relatedApp)
                    <a href="{{ route('creators.app', [$creator->slug, $relatedApp->slug]) }}" class="app-row group">
                        <div class="app-icon h-12 w-12 sm:h-[60px] sm:w-[60px]">
                            @if ($relatedApp->logoUrl())
                                <img src="{{ $relatedApp->logoUrl() }}" alt="" class="h-full w-full object-cover">
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex min-w-0 items-center gap-2">
                                <h3 class="truncate text-[15px] text-[#1D1D1F]">{{ $relatedApp->name }}</h3>
                                <x-platform-badge :platform="$relatedApp->platform" />
                            </div>
                            <p class="mt-0.5 truncate text-[13px] text-[#86868B]">
                                {{ \Illuminate\Support\Str::limit($relatedApp->description, 40) }}
                            </p>
                            <div class="mt-1">
                                <x-star-rating :rating="$relatedApp->averageRating()" :count="$relatedApp->ratingsCount()" />
                            </div>
                        </div>
                        <span class="btn-get" tabindex="-1">View</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif
</article>
@endsection
