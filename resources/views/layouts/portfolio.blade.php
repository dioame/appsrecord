<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portfolio') — {{ config('app.name', 'AppsRecord') }}</title>
    <meta name="description" content="@yield('meta_description', 'App portfolio')">
    <meta name="robots" content="index,follow">
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-body antialiased text-[#1D1D1F] bg-[#F5F5F7]">
    <main class="min-h-screen">
        @yield('content')
    </main>
    <style>[x-cloak]{display:none!important}</style>
</body>
</html>
