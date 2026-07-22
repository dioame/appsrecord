@props(['platform' => 'mobile'])

@php
    $platform = $platform ?: 'mobile';
    $label = \App\Models\AppListing::PLATFORM_LABELS[$platform]
        ?? \App\Models\AppListing::PLATFORM_LABELS['mobile'];
    $tone = match ($platform) {
        'web' => 'platform-badge-web',
        'desktop' => 'platform-badge-desktop',
        'others' => 'platform-badge-others',
        default => 'platform-badge-mobile',
    };
@endphp

<span {{ $attributes->merge(['class' => "platform-badge {$tone}"]) }} title="{{ $label }} app">
    <svg class="h-3 w-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
        @if ($platform === 'web')
            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 010 18M12 3a15 15 0 000 18"/>
        @elseif ($platform === 'desktop')
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        @elseif ($platform === 'others')
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"/>
        @else
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
        @endif
    </svg>
    <span>{{ $label }}</span>
</span>
