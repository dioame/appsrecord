@extends('layouts.public')

@section('title', 'Authors')

@section('content')
<section class="store-main-inner pb-12">
    <header class="mb-5 sm:mb-6">
        <h1 class="font-display text-[28px] font-bold tracking-tight text-[#1D1D1F] sm:text-[34px]">Authors</h1>
        <p class="mt-2 text-[13px] text-[#86868B] sm:text-[15px]">
            {{ $creators->count() }} {{ \Illuminate\Support\Str::plural('author', $creators->count()) }}.
        </p>
    </header>

    @if ($creators->isEmpty())
        <div class="rounded-[28px] bg-[#F5F5F7] px-6 py-14 text-center text-[15px] text-[#86868B]">
            No authors have published apps yet.
        </div>
    @else
        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($creators as $creator)
                <a
                    href="{{ $creator->slug ? route('creators.show', $creator->slug) : route('search', ['author' => $creator->name]) }}"
                    class="group flex items-center gap-3 rounded-2xl bg-[#F5F5F7] px-3 py-3 transition-opacity duration-150 hover:opacity-80"
                >
                    <div class="relative shrink-0">
                        <div class="author-avatar">
                            @if ($creator->avatarUrl())
                                <img src="{{ $creator->avatarUrl() }}" alt="" class="h-full w-full object-cover">
                            @else
                                <span>{{ $creator->initials() }}</span>
                            @endif
                        </div>
                        @if ($creator->isTrusted())
                            <x-trusted-check size="sm" class="absolute bottom-0 right-0" />
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-[14px] font-medium leading-tight text-[#1D1D1F] group-hover:text-[#0071E3]">{{ $creator->name }}</p>
                        <p class="mt-0.5 truncate text-[12px] leading-tight text-[#86868B]">
                            @if ($creator->apps_count > 0)
                                {{ $creator->apps_count }} {{ \Illuminate\Support\Str::plural('app', $creator->apps_count) }}
                            @else
                                No published apps
                            @endif
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    @endif
</section>
@endsection
