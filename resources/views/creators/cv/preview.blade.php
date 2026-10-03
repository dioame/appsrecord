@extends('layouts.portfolio')

@section('title', $creator->name.' — CV preview')
@section('meta_description', 'Preview and download '.$creator->name.'’s CV')

@section('content')
@php
    $templates = \App\Support\CvTemplates::all();
@endphp
<div class="mx-auto w-full max-w-[1100px] px-4 py-6 print:max-w-none print:p-0 sm:px-6 sm:py-8" x-data="{ printing: false }">
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3 print:hidden">
        <div>
            <a href="{{ route('creators.show', $creator->slug) }}" class="mb-2 inline-flex items-center gap-1.5 text-[13px] font-medium text-[#0071E3] hover:opacity-70">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                Back to portfolio
            </a>
            <h1 class="font-display text-[24px] font-bold tracking-tight text-[#1D1D1F] sm:text-[28px]">CV preview</h1>
            <p class="mt-1 text-[14px] text-[#86868B]">Choose a design, preview it, then download or print the PDF.</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button
                type="button"
                class="btn-secondary"
                @click="printing = true; $nextTick(() => { window.print(); printing = false })"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2M6 14h12v8H6v-8z"/>
                </svg>
                Print
            </button>
            <a href="{{ route('creators.cv', ['slug' => $creator->slug, 'template' => $template]) }}" class="btn-primary">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Download PDF
            </a>
        </div>
    </div>

    <div class="mb-5 grid gap-3 sm:grid-cols-3 print:hidden" role="listbox" aria-label="CV templates">
        @foreach ($templates as $key => $meta)
            <a
                href="{{ route('creators.cv.preview', ['slug' => $creator->slug, 'template' => $key]) }}"
                role="option"
                aria-selected="{{ $template === $key ? 'true' : 'false' }}"
                class="rounded-2xl border px-4 py-3 transition {{ $template === $key ? 'border-[#0071E3] bg-white shadow-sm ring-2 ring-[#0071E3]/20' : 'border-transparent bg-white/70 hover:bg-white' }}"
            >
                <p class="text-[14px] font-semibold text-[#1D1D1F]">{{ $meta['label'] }}</p>
                <p class="mt-1 text-[12px] leading-snug text-[#86868B]">{{ $meta['blurb'] }}</p>
                @if ($template === $key)
                    <p class="mt-2 text-[11px] font-semibold uppercase tracking-[0.06em] text-[#0071E3]">Selected</p>
                @endif
            </a>
        @endforeach
    </div>

    <p class="mb-3 text-center text-[12px] text-[#86868B] print:hidden">
        Previewing <span class="font-semibold text-[#1D1D1F]">{{ $templates[$template]['label'] }}</span>
        · A4 · matches the downloaded PDF
    </p>
    <div class="cv-paper-wrap">
        <div class="cv-preview-sheet">
            @include(\App\Support\CvTemplates::view($template), [
                'creator' => $creator,
                'apps' => $apps,
                'media' => $media,
            ])
        </div>
    </div>
</div>

<style>
    .cv-paper-wrap { overflow-x: auto; padding: 4px 0 24px; }
    .cv-preview-sheet {
        width: 210mm;
        max-width: 100%;
        min-height: 297mm;
        margin: 0 auto;
        padding: 14mm;
        background: #fff;
        box-shadow: 0 0 0 1px rgba(0,0,0,0.06), 0 12px 40px -8px rgba(0,0,0,0.18);
        border-radius: 4px;
        box-sizing: border-box;
    }
    @media (max-width: 800px) {
        .cv-preview-sheet { padding: 20px 16px; min-height: 0; }
    }
    .cv-preview-sheet .entry,
    .cv-preview-sheet .app { break-inside: avoid; page-break-inside: avoid; }
    .cv-preview-sheet h2 { break-after: avoid; page-break-after: avoid; }

    @page { size: A4; margin: 12mm 13mm; }
    @media print {
        html, body { background: #fff !important; }
        * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        main { min-height: 0 !important; }
        .cv-paper-wrap { overflow: visible; padding: 0; }
        .cv-preview-sheet {
            width: auto !important;
            max-width: none !important;
            min-height: 0 !important;
            padding: 0 !important;
            margin: 0 !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }
    }
</style>
@endsection
