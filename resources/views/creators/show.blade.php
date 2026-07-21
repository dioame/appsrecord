@extends('layouts.portfolio')

@section('title', $creator->name)
@section('meta_description', $creator->bio ?: 'Apps by '.$creator->name)

@section('content')
<div class="mx-auto w-full max-w-[980px] px-4 py-8 sm:px-6 sm:py-10">
    <header class="mb-8 flex flex-col gap-4 sm:mb-10 sm:flex-row sm:items-center sm:gap-5">
        <div class="author-avatar !h-16 !w-16 !text-[18px] sm:!h-20 sm:!w-20 sm:!text-[20px]">
            @if ($creator->avatar)
                <img src="{{ $creator->avatar }}" alt="" class="h-full w-full object-cover">
            @else
                <span>{{ $creator->initials() }}</span>
            @endif
        </div>
        <div class="min-w-0">
            <p class="text-[11px] font-semibold uppercase tracking-[0.08em] text-[#86868B]">App portfolio</p>
            <h1 class="mt-1 font-display text-[28px] font-bold tracking-tight text-[#1D1D1F] sm:text-[34px]">{{ $creator->name }}</h1>
            @if ($creator->bio)
                <p class="mt-1.5 max-w-2xl text-[14px] text-[#86868B] sm:text-[15px]">{{ $creator->bio }}</p>
            @endif
            <p class="mt-2 text-[13px] text-[#86868B]">
                {{ $apps->count() }} {{ \Illuminate\Support\Str::plural('app', $apps->count()) }}
                @if ($categories->isNotEmpty())
                    · {{ $categories->count() }} {{ \Illuminate\Support\Str::plural('category', $categories->count()) }}
                @endif
            </p>
        </div>
    </header>

    @if ($apps->isEmpty())
        <div class="rounded-2xl bg-white px-6 py-14 text-center text-[15px] text-[#86868B]">
            No published apps yet.
        </div>
    @else
        <div class="space-y-8">
            @foreach ($categories as $category)
                <section id="cat-{{ $category->slug ?? 'apps' }}">
                    <div class="mb-2 flex items-baseline justify-between gap-3">
                        <h2 class="section-title">{{ $category->name }}</h2>
                        <span class="text-[13px] text-[#86868B]">{{ $category->apps->count() }}</span>
                    </div>
                    <div class="rounded-[18px] bg-white px-2.5 sm:rounded-[22px] sm:px-4">
                        @foreach ($category->apps as $app)
                            <a href="{{ route('creators.app', [$creator->slug, $app->slug]) }}" class="app-row group">
                                <div class="app-icon h-12 w-12 sm:h-[60px] sm:w-[60px]">
                                    @if ($app->logoUrl())
                                        <img src="{{ $app->logoUrl() }}" alt="" class="h-full w-full object-cover">
                                    @else
                                        <div class="flex h-full w-full items-center justify-center bg-[#E8E8ED] text-[#86868B]">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4" stroke-width="1.5"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="truncate text-[14px] font-normal leading-tight text-[#1D1D1F] sm:text-[15px]">{{ $app->name }}</h3>
                                    <p class="mt-0.5 truncate text-[12px] leading-tight text-[#86868B] sm:text-[13px]">
                                        {{ $app->platformLabel() }} · {{ \Illuminate\Support\Str::limit($app->description, 48) }}
                                    </p>
                                    <div class="mt-1">
                                        <x-star-rating :rating="$app->averageRating()" :count="$app->ratingsCount()" />
                                    </div>
                                </div>
                                <span class="btn-get" tabindex="-1">View</span>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endforeach
        </div>
    @endif
</div>
@endsection
