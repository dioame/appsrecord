@extends('layouts.portfolio')

@section('title', $creator->name)
@section('meta_description', $creator->bio ?: 'Apps by '.$creator->name)

@section('content')
<div class="mx-auto w-full max-w-[720px] px-4 py-8 sm:px-6 sm:py-12">
    <header class="mb-8 text-center sm:mb-10">
        <div class="mx-auto author-avatar !h-20 !w-20 !text-[22px] sm:!h-24 sm:!w-24">
            @if ($creator->avatar)
                <img src="{{ $creator->avatar }}" alt="" class="h-full w-full object-cover">
            @else
                <span>{{ $creator->initials() }}</span>
            @endif
        </div>
        <h1 class="mt-4 font-display text-[28px] font-bold tracking-tight text-[#1D1D1F] sm:text-[34px]">{{ $creator->name }}</h1>
        @if ($creator->bio)
            <p class="mx-auto mt-2 max-w-md text-[15px] leading-relaxed text-[#86868B]">{{ $creator->bio }}</p>
        @endif
        <p class="mt-3 text-[13px] text-[#86868B]">
            {{ $apps->count() }} {{ \Illuminate\Support\Str::plural('app', $apps->count()) }}
        </p>
    </header>

    @if ($apps->isEmpty())
        <div class="rounded-2xl bg-white px-6 py-14 text-center text-[15px] text-[#86868B]">
            No published apps yet.
        </div>
    @else
        <ul class="space-y-3">
            @foreach ($apps as $app)
                @php
                    $href = $app->link ?: route('apps.public', $app->slug);
                    $external = filled($app->link);
                @endphp
                <li>
                    <a
                        href="{{ $href }}"
                        @if ($external) target="_blank" rel="noopener noreferrer" @endif
                        class="flex items-center gap-3.5 rounded-2xl bg-white p-3.5 shadow-[0_1px_2px_rgba(0,0,0,0.04)] transition hover:shadow-[0_4px_16px_rgba(0,0,0,0.06)] sm:gap-4 sm:p-4"
                    >
                        <div class="app-icon h-14 w-14 sm:h-16 sm:w-16">
                            @if ($app->logoUrl())
                                <img src="{{ $app->logoUrl() }}" alt="" class="h-full w-full object-cover">
                            @else
                                <div class="flex h-full w-full items-center justify-center bg-[#E8E8ED] text-[#86868B]">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4" stroke-width="1.5"/></svg>
                                </div>
                            @endif
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="truncate text-[16px] font-semibold text-[#1D1D1F]">{{ $app->name }}</p>
                            <p class="mt-0.5 truncate text-[13px] text-[#86868B]">
                                {{ $app->platformLabel() }} · {{ $app->category->name ?? 'App' }}
                            </p>
                            <div class="mt-1.5">
                                <x-star-rating :rating="$app->averageRating()" :count="$app->ratingsCount()" />
                            </div>
                        </div>

                        <span class="inline-flex shrink-0 items-center justify-center rounded-full bg-[#0071E3] px-4 py-2 text-[13px] font-semibold text-white">
                            {{ $external ? ($app->isWeb() ? 'Open' : 'Get') : 'View' }}
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@endsection
