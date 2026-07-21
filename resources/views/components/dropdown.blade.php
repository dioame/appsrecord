@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'py-1.5 bg-white'])

@php
$alignmentClasses = match ($align) {
    'left' => 'ltr:origin-top-left rtl:origin-top-right start-0',
    'top' => 'origin-top',
    default => 'ltr:origin-top-right rtl:origin-top-left end-0',
};

$width = match ($width) {
    '48' => 'w-52',
    default => $width,
};
@endphp

<div class="relative" x-data="{ userMenuOpen: false }" @click.outside="userMenuOpen = false" @close.stop="userMenuOpen = false">
    <div @click="userMenuOpen = ! userMenuOpen">
        {{ $trigger }}
    </div>

    <div
        x-show="userMenuOpen"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-[60] mt-2 {{ $width }} rounded-xl border border-[#E8E8ED] bg-white shadow-[0_8px_30px_rgba(0,0,0,0.12)] {{ $alignmentClasses }}"
        style="display: none;"
        @click="userMenuOpen = false"
    >
        <div class="{{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
