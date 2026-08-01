@props([
    'size' => 'md',
])

@php
    $sizeClass = match ($size) {
        'sm' => 'h-[14px] w-[14px]',
        'lg' => 'h-[22px] w-[22px]',
        default => 'h-[18px] w-[18px]',
    };
@endphp

<span
    {{ $attributes->class(['flex items-center justify-center rounded-full bg-[#0071E3] text-white ring-2 ring-white', $sizeClass]) }}
    title="Trusted developer"
    aria-label="Trusted developer"
>
    <svg class="h-[60%] w-[60%]" fill="none" stroke="currentColor" stroke-width="3.5" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
    </svg>
</span>
