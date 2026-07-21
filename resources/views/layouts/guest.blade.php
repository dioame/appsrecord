<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'AppsRecord') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-body text-[#1D1D1F] antialiased site-shell">
        <div class="flex min-h-screen flex-col items-center justify-center px-4 py-8">
            <a href="{{ route('home') }}" class="mb-6 flex items-center gap-2 cursor-pointer">
                <span class="flex h-9 w-9 items-center justify-center rounded-[10px] bg-gradient-to-br from-[#0071E3] to-[#5856D6] text-white">
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                        <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                        <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                        <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                    </svg>
                </span>
                <span class="font-display text-[20px] font-semibold">AppsRecord</span>
            </a>
            <div class="w-full max-w-md rounded-2xl bg-white px-5 py-6 shadow-[0_2px_16px_rgba(0,0,0,0.06)]">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
