@props([
    'user' => null,
    'size' => 'md',
])

@php
    $sizeClass = match ($size) {
        'sm' => 'h-6 w-6 text-[10px]',
        'lg' => 'h-10 w-10 text-[14px]',
        default => 'h-8 w-8 text-[12px]',
    };
    $url = $user?->avatarUrl();
    $initials = $user?->initials() ?? 'A';
@endphp

<span {{ $attributes->class(['author-avatar shrink-0', $sizeClass]) }}>
    @if ($url)
        <img src="{{ $url }}" alt="" class="h-full w-full object-cover">
    @else
        <span>{{ $initials }}</span>
    @endif
</span>
