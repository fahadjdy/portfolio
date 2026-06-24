@extends('layouts.app')

@section('title', ($post->seo_title ?: $post->title).' | Fahad Jadiya')
@section('description', $post->seo_description ?: ($post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 155)))
@section('og_type', 'article')
@section('og_image', $post->og_image ? img_url($post->og_image) : ($post->cover_image ? img_url($post->cover_image) : ''))

@push('head')
    @include('layouts.partials.adsense')
@endpush

@php
    $siteName = settings('site_name', 'Fahad Jadiya');
    $url = url()->current();
    $share = [
        ['label' => 'X', 'icon' => 'arrow-up-right', 'url' => 'https://twitter.com/intent/tweet?text='.rawurlencode($post->title).'&url='.rawurlencode($url)],
        ['label' => 'LinkedIn', 'icon' => 'linkedin', 'url' => 'https://www.linkedin.com/sharing/share-offsite/?url='.rawurlencode($url)],
    ];
@endphp

@section('content')
    {{-- Breadcrumb --}}
    <nav aria-label="Breadcrumb" class="border-b border-slate-100 bg-slate-50">
        <div class="container-px py-3">
            <ol class="flex flex-wrap items-center gap-1.5 text-sm text-slate-500">
                <li><a href="{{ route('home') }}" class="hover:text-brand-700">Home</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('blog.index') }}" class="hover:text-brand-700">Blog</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-medium text-slate-700" aria-current="page">{{ \Illuminate\Support\Str::limit($post->title, 50) }}</li>
            </ol>
        </div>
    </nav>

    {{-- Header --}}
    <header class="relative overflow-hidden border-b border-slate-100">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-brand-50/60 to-white"></div>
        <div class="container-px py-12 sm:py-16">
            <div class="mx-auto max-w-3xl text-center">
                @if($post->category)
                    <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="inline-block rounded-full bg-brand-100 px-3 py-1 text-xs font-semibold text-brand-700 hover:bg-brand-200">{{ $post->category->name }}</a>
                @endif
                <h1 class="mt-4 text-3xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-4xl lg:text-5xl">{{ $post->title }}</h1>
                @if($post->excerpt)<p class="mx-auto mt-4 max-w-2xl text-lg text-slate-600">{{ $post->excerpt }}</p>@endif

                <div class="mt-6 flex items-center justify-center gap-3 text-sm text-slate-500">
                    <span class="grid h-9 w-9 place-items-center rounded-full bg-brand-600 text-xs font-bold text-white">{{ \Illuminate\Support\Str::substr($siteName, 0, 2) }}</span>
                    <div class="text-left">
                        <p class="font-medium text-slate-700">{{ $siteName }}</p>
                        <p class="text-xs">{{ $post->published_at?->format('d M Y') }}@if($post->reading_minutes) · {{ $post->reading_minutes }} min read @endif</p>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <article class="container-px py-12">
        <div class="mx-auto max-w-3xl">
            @if($post->cover_image)
                <figure class="mb-10 overflow-hidden rounded-2xl border border-slate-200 shadow-sm">
                    <x-picture :src="img_url($post->cover_image)" :alt="$post->title" :eager="true" class="w-full" />
                </figure>
            @endif

            <div class="prose-content text-[1.0625rem] leading-8 text-slate-700 [&_a]:font-medium [&_a]:text-brand-700 [&_a]:underline [&_h2]:mt-10 [&_h2]:font-display [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-slate-900 [&_h3]:mt-7 [&_h3]:font-display [&_h3]:text-xl [&_h3]:font-semibold [&_h3]:text-slate-900 [&_li]:my-1.5 [&_ol]:my-5 [&_ol]:list-decimal [&_ol]:pl-6 [&_p]:my-5 [&_pre]:my-6 [&_pre]:overflow-x-auto [&_pre]:rounded-xl [&_pre]:bg-slate-900 [&_pre]:p-4 [&_pre]:text-sm [&_pre]:leading-relaxed [&_pre]:text-slate-100 [&_code]:rounded [&_code]:bg-slate-100 [&_code]:px-1.5 [&_code]:py-0.5 [&_code]:text-[0.9em] [&_code]:text-brand-700 [&_pre_code]:bg-transparent [&_pre_code]:p-0 [&_pre_code]:text-slate-100 [&_ul]:my-5 [&_ul]:list-disc [&_ul]:pl-6">
                {!! $post->body !!}
            </div>

            {{-- Tags + share --}}
            <div class="mt-10 flex flex-wrap items-center justify-between gap-4 border-t border-slate-100 pt-6">
                <div class="flex flex-wrap gap-1.5">
                    @foreach($post->techTags as $tag)
                        <x-badge>{{ $tag->name }}</x-badge>
                    @endforeach
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-slate-400">Share</span>
                    @foreach($share as $s)
                        <a href="{{ $s['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="Share on {{ $s['label'] }}"
                           class="grid h-9 w-9 place-items-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-brand-300 hover:text-brand-700">
                            <x-icon :name="$s['icon']" class="h-4 w-4" />
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Author box --}}
            <div class="mt-10 flex flex-col items-start gap-4 rounded-2xl border border-slate-200 bg-slate-50 p-6 sm:flex-row sm:items-center">
                <span class="grid h-14 w-14 shrink-0 place-items-center rounded-full bg-brand-600 text-lg font-bold text-white">{{ \Illuminate\Support\Str::substr($siteName, 0, 2) }}</span>
                <div class="flex-1">
                    <p class="font-display font-bold text-slate-900">{{ $siteName }}</p>
                    <p class="mt-0.5 text-sm text-slate-600">{{ settings('tagline', 'Senior Full-Stack Developer') }} — building scalable web apps, SaaS platforms and AI products with Laravel &amp; Vue.</p>
                </div>
                <a href="{{ route('contact') }}" class="btn-primary shrink-0">Work with me</a>
            </div>
        </div>
    </article>

    {{-- Related --}}
    @if($related->isNotEmpty())
        <section class="border-t border-slate-200 bg-slate-50">
            <div class="container-px py-14">
                <h2 class="mb-6 font-display text-xl font-bold text-slate-900">Keep reading</h2>
                <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($related as $rel)
                        <x-card.post :post="$rel" />
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
