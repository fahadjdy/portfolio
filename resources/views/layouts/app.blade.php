<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- SEO meta (made fully dynamic from DB settings in Phase 4) --}}
    @php
        $metaTitle = trim($__env->yieldContent('title')) ?: 'Fahad Jadiya — Senior Full-Stack Developer';
        $metaDescription = trim($__env->yieldContent('description'))
            ?: 'Fahad Jadiya is a Senior Full-Stack Developer specializing in Laravel, Vue, and scalable web applications, CRMs, SaaS platforms and AI integrations.';
        $canonical = $__env->yieldContent('canonical') ?: url()->current();
    @endphp
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta name="robots" content="{{ app()->environment('production') ? 'index, follow' : 'noindex, nofollow' }}">

    {{-- Open Graph / Twitter (expanded in Phase 4) --}}
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta name="twitter:card" content="summary_large_image">

    {{-- Fonts (Bunny = privacy-friendly Google Fonts mirror) --}}
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600|sora:600,700,800&display=swap" rel="stylesheet">

    <link rel="icon" href="/favicon.ico" sizes="any">

    @vite(['resources/css/public.css', 'resources/js/public.js'])
    @stack('head')
</head>
<body class="min-h-screen bg-white">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-brand-600 focus:px-4 focus:py-2 focus:text-white">Skip to content</a>

    @include('layouts.partials.header')

    <main id="main">
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    @stack('scripts')
</body>
</html>
