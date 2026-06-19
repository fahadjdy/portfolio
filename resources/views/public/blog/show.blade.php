@extends('layouts.app')

@section('title', ($post->seo_title ?: $post->title).' | Fahad Jadiya')
@section('description', $post->seo_description ?: ($post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->body), 155)))
@section('og_type', 'article')
@section('og_image', $post->og_image ? img_url($post->og_image) : ($post->cover_image ? img_url($post->cover_image) : ''))

@section('content')
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

    <article class="container-px py-12 sm:py-16">
        <div class="mx-auto max-w-3xl">
            <div class="flex flex-wrap items-center gap-2 text-sm font-medium text-slate-500">
                @if($post->category)<span class="text-brand-700">{{ $post->category->name }}</span><span aria-hidden="true">·</span>@endif
                <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('d M Y') }}</time>
                @if($post->reading_minutes)<span aria-hidden="true">·</span><span>{{ $post->reading_minutes }} min read</span>@endif
            </div>
            <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-5xl">{{ $post->title }}</h1>
            @if($post->excerpt)<p class="mt-4 text-lg text-slate-600">{{ $post->excerpt }}</p>@endif

            @if($post->cover_image)
                <div class="mt-8 overflow-hidden rounded-2xl border border-slate-200">
                    <x-picture :src="img_url($post->cover_image)" :alt="$post->title" :eager="true" class="w-full" />
                </div>
            @endif

            <div class="prose-content mt-8 leading-relaxed text-slate-700 [&_a]:text-brand-700 [&_a]:underline [&_h2]:mt-8 [&_h2]:font-display [&_h2]:text-2xl [&_h2]:font-bold [&_h2]:text-slate-900 [&_h3]:mt-6 [&_h3]:font-display [&_h3]:text-xl [&_h3]:font-semibold [&_h3]:text-slate-900 [&_li]:my-1 [&_ol]:my-4 [&_ol]:list-decimal [&_ol]:pl-6 [&_p]:my-4 [&_pre]:my-4 [&_pre]:overflow-x-auto [&_pre]:rounded-lg [&_pre]:bg-slate-900 [&_pre]:p-4 [&_pre]:text-sm [&_pre]:text-slate-100 [&_ul]:my-4 [&_ul]:list-disc [&_ul]:pl-6">
                {!! $post->body !!}
            </div>

            @if($post->techTags->isNotEmpty())
                <div class="mt-8 flex flex-wrap gap-1.5 border-t border-slate-100 pt-6">
                    @foreach($post->techTags as $tag)
                        <x-badge>{{ $tag->name }}</x-badge>
                    @endforeach
                </div>
            @endif
        </div>
    </article>

    @if($related->isNotEmpty())
        <x-section eyebrow="Keep reading" title="Related articles" class="bg-slate-50">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($related as $rel)
                    <x-card.post :post="$rel" />
                @endforeach
            </div>
        </x-section>
    @endif

    <section class="border-t border-slate-200">
        <div class="container-px py-12 text-center">
            <h2 class="font-display text-2xl font-bold text-slate-900">Have a project in mind?</h2>
            <a href="{{ route('contact') }}" class="btn-primary mt-5">Get in touch <x-icon name="arrow-right" class="h-4 w-4" /></a>
        </div>
    </section>
@endsection
