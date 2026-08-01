@php
    $dev = $app->user ?? null;
@endphp
<div class="stat-cell">
    <p class="stat-label">Developer</p>
    <div class="mt-1">
        <x-developer-avatar :user="$dev" size="md" class="!shadow-none" />
    </div>
    <p class="stat-sub mt-1.5 flex max-w-full flex-col items-center gap-1">
        <span class="flex max-w-full items-center justify-center gap-1">
            @if ($dev?->slug)
                <a href="{{ route('creators.show', $dev->slug) }}" class="truncate text-[#0071E3] hover:underline cursor-pointer">{{ $app->authorName() }}</a>
            @else
                <a href="{{ route('search', ['author' => $app->authorName()]) }}" class="truncate text-[#0071E3] hover:underline cursor-pointer">{{ $app->authorName() }}</a>
            @endif
            <x-trusted-badge :user="$dev" />
        </span>
    </p>
</div>
