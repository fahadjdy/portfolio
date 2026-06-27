@extends('layouts.app')

@section('title', 'Blog — Fahad Jadiya')
@section('description', 'Articles on Laravel, Vue, SaaS architecture, AI integration and full-stack engineering by Fahad Jadiya.')
@section('canonical', route('blog.index'))

@push('head')
    @include('layouts.partials.adsense')
@endpush

@section('content')
    {{-- ===================== HEADER ===================== --}}
    <section class="relative overflow-hidden border-b border-slate-100">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-brand-50/70 via-white to-white"></div>
        <div class="absolute inset-x-0 top-0 -z-10 h-64 bg-grid"></div>
        <div class="container-px py-16 sm:py-20">
            <div class="mx-auto max-w-2xl text-center">
                <p class="eyebrow">Writing &amp; insights</p>
                <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl">The Blog</h1>
                <p class="mt-4 text-lg text-slate-600">
                    Notes on Laravel, Vue, SaaS architecture and AI — distilled from real production work.
                </p>
            </div>

            {{-- Category filter --}}
            @if($categories->isNotEmpty())
                <div class="mt-8 flex flex-wrap items-center justify-center gap-2">
                    <a href="{{ route('blog.index') }}"
                       @class([
                           'rounded-full px-4 py-1.5 text-sm font-medium transition',
                           'bg-brand-600 text-white shadow-sm' => ! $activeCategory,
                           'border border-slate-200 bg-white text-slate-600 hover:border-brand-300 hover:text-brand-700' => $activeCategory,
                       ])>All posts</a>
                    @foreach($categories as $cat)
                        <a href="{{ route('blog.index', ['category' => $cat->slug]) }}"
                           @class([
                               'rounded-full px-4 py-1.5 text-sm font-medium transition',
                               'bg-brand-600 text-white shadow-sm' => $activeCategory === $cat->slug,
                               'border border-slate-200 bg-white text-slate-600 hover:border-brand-300 hover:text-brand-700' => $activeCategory !== $cat->slug,
                           ])>{{ $cat->name }}</a>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <div class="container-px py-14 sm:py-16">
        @if($featured)
            {{-- ===================== FEATURED ===================== --}}
            <a href="{{ route('blog.show', $featured) }}"
               class="group mb-14 grid overflow-hidden rounded-3xl border border-slate-200 bg-white transition hover:border-brand-200 hover:shadow-xl lg:grid-cols-2">
                <div class="relative aspect-[16/10] overflow-hidden bg-slate-100 lg:aspect-auto">
                    @if($featured->cover_image)
                        <x-picture :src="img_url($featured->cover_image)" :alt="$featured->title" :eager="true" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" />
                    @else
                        <div class="flex h-full min-h-[260px] w-full items-center justify-center bg-gradient-to-br from-brand-500 via-brand-600 to-brand-800 p-8 text-center">
                            <span class="font-display text-2xl font-bold text-white/95">{{ $featured->title }}</span>
                        </div>
                    @endif
                    <span class="absolute left-4 top-4 rounded-full bg-amber-400 px-3 py-1 text-xs font-bold text-slate-900 shadow">★ Featured</span>
                </div>
                <div class="flex flex-col justify-center p-7 sm:p-10">
                    <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
                        @if($featured->category)<span class="text-brand-700">{{ $featured->category->name }}</span><span aria-hidden="true">·</span>@endif
                        <time datetime="{{ $featured->published_at?->toDateString() }}">{{ $featured->published_at?->format('d M Y') }}</time>
                        @if($featured->reading_minutes)<span aria-hidden="true">·</span><span>{{ $featured->reading_minutes }} min read</span>@endif
                    </div>
                    <h2 class="mt-3 font-display text-2xl font-bold leading-tight text-slate-900 transition group-hover:text-brand-700 sm:text-3xl">{{ $featured->title }}</h2>
                    @if($featured->excerpt)<p class="mt-3 line-clamp-3 text-slate-600">{{ $featured->excerpt }}</p>@endif
                    <span class="mt-6 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-700">
                        Read the article <x-icon name="arrow-right" class="h-4 w-4 transition group-hover:translate-x-1" />
                    </span>
                </div>
            </a>
        @endif

        {{-- ===================== GRID ===================== --}}
        @if($posts->count())
            @if($featured)
                <h2 class="mb-6 font-display text-lg font-bold text-slate-900">Latest articles</h2>
            @endif
            <div class="grid gap-7 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($posts as $post)
                    <x-card.post :post="$post" />
                @endforeach
            </div>

            @if($posts->hasPages())
                <div class="mt-12">{{ $posts->onEachSide(1)->links() }}</div>
            @endif
        @elseif(! $featured)
            <div class="rounded-3xl border border-dashed border-slate-300 py-20 text-center">
                <div class="mx-auto grid h-12 w-12 place-items-center rounded-full bg-slate-100 text-slate-400">
                    <x-icon name="layers" class="h-6 w-6" />
                </div>
                <p class="mt-4 text-slate-500">No articles {{ $activeCategory ? 'in this category' : 'published' }} yet — check back soon.</p>
                @if($activeCategory)<a href="{{ route('blog.index') }}" class="mt-3 inline-block text-sm font-semibold text-brand-700">View all posts</a>@endif
            </div>
        @endif
    </div>

    {{-- ===================== CTA ===================== --}}
    <section class="border-t border-slate-200 bg-slate-50">
        <div class="container-px py-14 text-center">
            <h2 class="font-display text-2xl font-bold text-slate-900">Have a project in mind?</h2>
            <p class="mx-auto mt-2 max-w-xl text-slate-600">I build scalable web apps, SaaS platforms and AI-powered products. Let's talk.</p>
            <a href="{{ route('contact') }}" class="btn-primary mt-6">Start a project <x-icon name="arrow-right" class="h-4 w-4" /></a>
        </div>
    </section>
@endsection
