@props([
    'tag',
    'link' => true,
    'active' => false,
    'query' => [],
])

@php
    $classes = 'filter-chip '.($active ? 'filter-chip-active' : '');
    $hrefQuery = array_filter(array_merge($query, ['tags' => $tag->slug]));
@endphp

@if ($link)
    <a
        href="{{ route('search', $hrefQuery) }}"
        {{ $attributes->merge(['class' => $classes]) }}
    >{{ $tag->name }}</a>
@else
    <span {{ $attributes->merge(['class' => $classes]) }}>{{ $tag->name }}</span>
@endif
