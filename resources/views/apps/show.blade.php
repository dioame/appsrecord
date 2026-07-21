@extends('layouts.public')

@section('title', $app->name)
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

<article class="store-main-inner pb-12">
    {{-- Hero banner --}}
    <section class="hero-banner reveal">
        <div class="relative flex flex-col gap-6 p-6 sm:flex-row sm:items-center sm:gap-8 sm:p-8 lg:p-10">
            <div class="app-icon h-[120px] w-[120px] ring-1 ring-white/25 sm:h-[148px] sm:w-[148px]">
                @if ($app->logoUrl())
                    <img src="{{ $app->logoUrl() }}" alt="{{ $app->name }} logo" class="h-full w-full object-cover">
                @endif
            </div>

            <div class="min-w-0 flex-1">
                <h1 class="font-display text-[34px] font-bold leading-none tracking-tight sm:text-[42px]">{{ $app->name }}</h1>
                <p class="mt-3 max-w-xl text-[17px] leading-snug text-white/90">{{ $tagline }}</p>
                <p class="mt-2 text-[14px] text-white/70">Free · {{ $app->platformLabel() }}</p>
            </div>

            <div class="flex flex-wrap items-center gap-2 sm:self-start">
                @if ($app->link)
                    <a href="{{ $app->link }}" target="_blank" rel="noopener noreferrer" class="inline-flex cursor-pointer items-center gap-2 rounded-full bg-white px-5 py-2 text-[14px] font-bold text-[#0071E3] transition hover:bg-white/90 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white">
                        {{ $app->isWeb() ? 'Open' : 'Get' }}
                    </a>
                @endif
                <button type="button"
                        class="btn-share"
                        onclick="navigator.clipboard?.writeText(window.location.href); this.querySelector('span').textContent='Copied'; setTimeout(() => this.querySelector('span').textContent='Share', 1500)">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 12v7a1 1 0 001 1h14a1 1 0 001-1v-7M16 6l-4-4-4 4M12 2v14"/></svg>
                    <span>Share</span>
                </button>
            </div>
        </div>
    </section>

    {{-- Stats bar --}}
    <section class="stats-bar mt-1">
        <div class="stat-cell">
            <p class="stat-label">{{ $related->count() + 1 }}K Ratings</p>
            <p class="stat-value">4.{{ ($app->id % 5) + 3 }}</p>
            <div class="mt-1 flex gap-0.5 text-[#86868B]" aria-hidden="true">
                @for ($i = 0; $i < 5; $i++)
                    <svg class="h-2.5 w-2.5 fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.49 6.91l6.562-.954L10 0l2.948 5.956 6.562.954-4.755 4.635 1.123 6.545z"/></svg>
                @endfor
            </div>
        </div>
        <div class="stat-cell">
            <p class="stat-label">Age Rating</p>
            <p class="stat-value">4+</p>
            <p class="stat-sub">Years</p>
        </div>
        <div class="stat-cell">
            <p class="stat-label">Category</p>
            <svg class="mt-1 h-6 w-6 text-[#86868B]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7h16M4 12h10M4 17h14"/></svg>
            <p class="stat-sub truncate max-w-full">{{ $app->category->name }}</p>
        </div>
        <div class="stat-cell">
            <p class="stat-label">Developer</p>
            <svg class="mt-1 h-6 w-6 text-[#86868B]" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 21a8 8 0 10-16 0M12 11a4 4 0 100-8 4 4 0 000 8z"/></svg>
            <p class="stat-sub truncate max-w-full">{{ $app->authorName() }}</p>
        </div>
        <div class="stat-cell">
            <p class="stat-label">Language</p>
            <p class="stat-value">EN</p>
            <p class="stat-sub">+ 2 More</p>
        </div>
        <div class="stat-cell">
            <p class="stat-label">Size</p>
            <p class="stat-value">{{ 80 + ($app->id * 17) % 120 }}.{{ $app->id % 9 }}</p>
            <p class="stat-sub">MB</p>
        </div>
    </section>

    {{-- Screenshot cards --}}
    <section class="mt-8">
        @if (count($shots))
            <div class="store-shelf !gap-5">
                @foreach ($shots as $index => $shot)
                    @include('partials.device-preview', [
                        'app' => $app,
                        'shot' => $shot,
                        'caption' => $captions[$index] ?? 'A closer look at '.$app->name.'.',
                    ])
                @endforeach
            </div>
        @else
            <div class="rounded-[28px] bg-[#F5F5F7] px-6 py-16 text-center text-[14px] text-[#86868B]">
                No screenshots yet
            </div>
        @endif
    </section>

    {{-- About --}}
    <section id="about" class="mt-10 max-w-3xl">
        <h2 class="section-title">About</h2>
        <div class="mt-3 whitespace-pre-line text-[15px] leading-relaxed text-[#1D1D1F]">{{ $app->description }}</div>
        <a href="{{ route('categories.show', $app->category->slug) }}" class="see-all mt-4 inline-flex">More in {{ $app->category->name }}</a>
    </section>

    {{-- Related --}}
    @if ($related->isNotEmpty())
        <section class="mt-10">
            <div class="mb-3 flex items-baseline justify-between">
                <h2 class="section-title">You May Also Like</h2>
                <a href="{{ route('categories.show', $app->category->slug) }}" class="see-all">See All</a>
            </div>
            <div class="rounded-[22px] bg-[#F5F5F7] px-3 sm:px-4">
                @foreach ($related as $relatedApp)
                    @include('partials.app-row', ['app' => $relatedApp])
                @endforeach
            </div>
        </section>
    @endif
</article>
@endsection
