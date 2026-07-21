@props([
    'rating' => 0,
    'count' => null,
    'size' => 'sm',
    'showValue' => true,
    'light' => false,
])

@php
    $rating = max(0, min(5, (float) $rating));
    $full = (int) floor($rating);
    $half = ($rating - $full) >= 0.5;
    $starClass = match ($size) {
        'lg' => 'h-5 w-5',
        'md' => 'h-4 w-4',
        default => 'h-3.5 w-3.5',
    };
    $filled = $light ? 'text-amber-300' : 'text-amber-400';
    $empty = $light ? 'text-white/35' : 'text-[#D2D2D7]';
    $text = $light ? 'text-white/85' : 'text-[#86868B]';
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1']) }} title="{{ $rating > 0 ? number_format($rating, 1).' out of 5' : 'No ratings yet' }}">
    <span class="inline-flex items-center gap-0.5" aria-hidden="true">
        @for ($i = 1; $i <= 5; $i++)
            @if ($i <= $full)
                <svg class="{{ $starClass }} {{ $filled }}" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            @elseif ($i === $full + 1 && $half)
                <svg class="{{ $starClass }} {{ $filled }}" viewBox="0 0 20 20" fill="currentColor"><defs><linearGradient id="half-{{ $i }}-{{ md5((string) $rating.$size.$light) }}"><stop offset="50%" stop-color="currentColor"/><stop offset="50%" stop-color="{{ $light ? 'rgba(255,255,255,0.35)' : '#D2D2D7' }}" stop-opacity="1"/></linearGradient></defs><path fill="url(#half-{{ $i }}-{{ md5((string) $rating.$size.$light) }})" d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            @else
                <svg class="{{ $starClass }} {{ $empty }}" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            @endif
        @endfor
    </span>
    @if ($showValue)
        <span class="text-[12px] tabular-nums {{ $text }}">
            @if ($rating > 0)
                {{ number_format($rating, 1) }}
                @if ($count !== null)
                    <span class="opacity-80">({{ $count }})</span>
                @endif
            @else
                —
            @endif
        </span>
    @endif
</span>
