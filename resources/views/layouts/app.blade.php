<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Google Analytics (GA4) — loads only when an ID is set, and only in production. --}}
    @if(($gaId = settings('google_analytics_id')) && app()->environment('production'))
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '{{ $gaId }}');
        </script>
    @endif

    {{-- SEO meta (settings-driven; per-page overrides via @section). --}}
    @php
        $siteName = settings('site_name', 'Fahad Jadiya');
        $metaTitle = trim($__env->yieldContent('title')) ?: settings('meta_title', $siteName.' — Senior Full-Stack Developer');
        $metaDescription = trim($__env->yieldContent('description')) ?: settings('meta_description', 'Senior Full-Stack Developer specializing in Laravel, Vue and scalable web apps.');
        $metaKeywords = trim($__env->yieldContent('keywords')) ?: settings('meta_keywords');
        // Explicit, query-free canonical: pages pass an absolute route(); listings/filters
        // fall back to the clean current path so duplicate variants consolidate.
        $canonical = trim($__env->yieldContent('canonical')) ?: url()->current();
        $ogType = trim($__env->yieldContent('og_type')) ?: 'website';
        $ogImage = trim($__env->yieldContent('og_image')) ?: setting_image('og_default_image');
        $favicon = setting_image('favicon');
        $twitterHandle = ($th = trim((string) settings('twitter_handle'))) !== '' ? '@'.ltrim($th, '@') : null;
        $themeColor = settings('theme_color', '#4f46e5');
        $author = settings('organization_name', $siteName);
    @endphp
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    @if($metaKeywords)<meta name="keywords" content="{{ $metaKeywords }}">@endif
    <meta name="author" content="{{ $author }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta name="robots" content="{{ app()->environment('production') ? 'index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1' : 'noindex, nofollow' }}">
    <meta name="theme-color" content="{{ $themeColor }}">

    {{-- Open Graph / Twitter --}}
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:site_name" content="{{ $siteName }}">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:locale" content="en_US">
    @if($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
        <meta property="og:image:alt" content="{{ $metaTitle }}">
    @endif
    @if($ogType === 'article')
        @hasSection('article_published_time')<meta property="article:published_time" content="@yield('article_published_time')">@endif
        @hasSection('article_modified_time')<meta property="article:modified_time" content="@yield('article_modified_time')">@endif
        <meta property="article:author" content="{{ $author }}">
    @endif
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    @if($twitterHandle)
        <meta name="twitter:site" content="{{ $twitterHandle }}">
        <meta name="twitter:creator" content="{{ $twitterHandle }}">
    @endif
    @if($ogImage)<meta name="twitter:image" content="{{ $ogImage }}">@endif

    {{-- Fonts (Bunny = privacy-friendly Google Fonts mirror) --}}
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600|sora:600,700,800&display=swap" rel="stylesheet">

    <link rel="icon" href="{{ $favicon ?: '/favicon.ico' }}" sizes="any">

    @vite(['resources/css/public.css', 'resources/js/public.js'])

    {{-- JSON-LD structured data (GEO) — one connected @graph per page --}}
    @isset($schema)
        <script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @endisset

    @stack('head')
</head>
<body class="min-h-screen bg-white">
    <a href="#main" class="sr-only focus:not-sr-only focus:absolute focus:left-4 focus:top-4 focus:z-50 focus:rounded-lg focus:bg-brand-600 focus:px-4 focus:py-2 focus:text-white">Skip to content</a>

    @include('layouts.partials.header')

    @if(session('success') || session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 6000)" x-transition
             class="fixed inset-x-0 top-20 z-50 mx-auto w-fit max-w-[90%] rounded-xl px-5 py-3 text-sm font-medium shadow-lg {{ session('error') ? 'bg-rose-600 text-white' : 'bg-emerald-600 text-white' }}">
            {{ session('success') ?: session('error') }}
        </div>
    @endif

    <main id="main">
        @yield('content')
    </main>

    @include('layouts.partials.footer')

    @include('layouts.partials.whatsapp')

    @stack('scripts')
</body>
</html>
