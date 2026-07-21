@extends('layouts.public')

@section('title', $creator->name.' — Apps')
@section('meta_description', $creator->bio ?: 'Browse apps by '.$creator->name.' on AppsRecord.')

@section('content')
<section class="store-main-inner pb-12">
    <header class="mb-6 overflow-hidden rounded-2xl bg-gradient-to-br from-[#0A84FF] via-[#0071E3] to-[#0040DD] p-5 text-white shadow-[0_8px_30px_rgba(0,113,227,0.22)] sm:mb-8 sm:rounded-[28px] sm:p-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:gap-6">
            <div class="author-avatar !h-20 !w-20 !text-[22px] ring-2 ring-white/25 sm:!h-24 sm:!w-24">
                @if ($creator->avatar)
                    <img src="{{ $creator->avatar }}" alt="" class="h-full w-full object-cover">
                @else
                    <span>{{ $creator->initials() }}</span>
                @endif
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-[11px] font-semibold uppercase tracking-[0.1em] text-white/70">Creator portfolio</p>
                <h1 class="mt-1 font-display text-[28px] font-bold leading-tight tracking-tight sm:text-[36px]">{{ $creator->name }}</h1>
                @if ($creator->bio)
                    <p class="mt-2 max-w-2xl text-[14px] text-white/85 sm:text-[15px]">{{ $creator->bio }}</p>
                @else
                    <p class="mt-2 text-[14px] text-white/75">Published apps ready to explore.</p>
                @endif
                <p class="mt-3 text-[13px] text-white/65">
                    {{ $apps->count() }} {{ \Illuminate\Support\Str::plural('app', $apps->count()) }}
                </p>
            </div>
            <button
                type="button"
                class="btn-share shrink-0 self-start"
                x-data="{ copied: false }"
                @click="navigator.clipboard?.writeText(@js(route('creators.show', $creator->slug))); copied = true; setTimeout(() => copied = false, 1600)"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
                <span x-text="copied ? 'Copied' : 'Share'"></span>
            </button>
        </div>
    </header>

    @if ($apps->isEmpty())
        <div class="rounded-[22px] bg-[#F5F5F7] px-6 py-14 text-center text-[15px] text-[#86868B]">
            This creator has not published any apps yet.
        </div>
    @else
        <div class="mb-3 flex items-baseline justify-between gap-3">
            <h2 class="section-title">Apps</h2>
        </div>
        <div class="rounded-[18px] bg-[#F5F5F7] px-2.5 sm:rounded-[22px] sm:px-4">
            @foreach ($apps as $app)
                @include('partials.app-row', ['app' => $app, 'showAuthor' => false])
            @endforeach
        </div>
    @endif
</section>
@endsection
