@extends('layouts.app')

@section('title', ($project->seo_title ?: $project->title.' — Case Study').' | Fahad Jadiya')
@section('description', $project->seo_description ?: \Illuminate\Support\Str::limit($project->summary, 155))
@section('og_type', 'article')
@section('og_image', $project->og_image ? img_url($project->og_image) : ($project->cover_image ? img_url($project->cover_image) : ''))
@section('canonical', route('projects.show', $project->slug))
@section('keywords', $project->relationLoaded('techTags') ? $project->techTags->pluck('name')->implode(', ') : '')
@section('article_modified_time', optional($project->updated_at)->toAtomString())

@push('head')
    @include('layouts.partials.adsense')
@endpush

@section('content')
    {{-- Breadcrumbs --}}
    <nav aria-label="Breadcrumb" class="border-b border-slate-100 bg-slate-50">
        <div class="container-px py-3">
            <ol class="flex flex-wrap items-center gap-1.5 text-sm text-slate-500">
                <li><a href="{{ route('home') }}" class="hover:text-brand-700">Home</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('projects.index') }}" class="hover:text-brand-700">Projects</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-medium text-slate-700" aria-current="page">{{ $project->title }}</li>
            </ol>
        </div>
    </nav>

    {{-- Header --}}
    <header class="bg-gradient-to-b from-brand-50 to-white">
        <div class="container-px py-14 sm:py-16">
            <div class="mx-auto max-w-3xl">
                <div class="flex flex-wrap items-center gap-2 text-sm font-medium text-slate-500">
                    <span class="text-brand-700">{{ $project->category }}</span>
                    @if($project->year)<span aria-hidden="true">·</span><span>{{ $project->year }}</span>@endif
                    @if($project->role)<span aria-hidden="true">·</span><span>{{ $project->role }}</span>@endif
                </div>
                <h1 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-5xl">{{ $project->title }}</h1>
                <p class="mt-4 text-lg text-slate-600">{{ $project->summary }}</p>

                <div class="mt-6 flex flex-wrap gap-3">
                    @if($project->live_url)
                        <a href="{{ $project->live_url }}" target="_blank" rel="noopener noreferrer" class="btn-primary">
                            Visit live site <x-icon name="external-link" class="h-4 w-4" />
                        </a>
                    @endif
                    @if($project->repo_url)
                        <a href="{{ $project->repo_url }}" target="_blank" rel="noopener noreferrer" class="btn-ghost">
                            <x-icon name="github" class="h-4 w-4" /> Source
                        </a>
                    @endif
                </div>

                @if($project->techTags->isNotEmpty())
                    <div class="mt-6 flex flex-wrap gap-1.5">
                        @foreach($project->techTags as $tag)
                            <x-badge :href="route('projects.index', ['tech' => $tag->slug])">{{ $tag->name }}</x-badge>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </header>

    @if($project->cover_image)
        <div class="container-px">
            <div class="mx-auto max-w-5xl overflow-hidden rounded-2xl border border-slate-200 shadow-sm">
                <x-picture :src="img_url($project->cover_image)" :alt="$project->title" :eager="true" class="w-full" />
            </div>
        </div>
    @endif

    <div class="container-px py-14 sm:py-16">
        <div class="mx-auto max-w-3xl space-y-12">
            {{-- Overview: problem / solution / outcome --}}
            @if($project->problem || $project->solution || $project->outcome)
                <div class="grid gap-6 sm:grid-cols-3">
                    @foreach(['problem' => 'The challenge', 'solution' => 'The solution', 'outcome' => 'The outcome'] as $field => $label)
                        @if($project->{$field})
                            <div class="rounded-2xl border border-slate-200 bg-white p-5">
                                <h2 class="text-xs font-semibold uppercase tracking-wide text-brand-600">{{ $label }}</h2>
                                <p class="mt-2 text-sm leading-relaxed text-slate-600">{{ $project->{$field} }}</p>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            {{-- Highlights --}}
            @if(! empty($project->highlights))
                <div>
                    <h2 class="font-display text-2xl font-bold text-slate-900">Key highlights</h2>
                    <ul class="mt-5 grid gap-3 sm:grid-cols-2">
                        @foreach($project->highlights as $highlight)
                            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white p-4 text-sm text-slate-700">
                                <x-icon name="check-circle" class="mt-0.5 h-5 w-5 shrink-0 text-brand-600" />
                                <span>{{ $highlight }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- PANELS & FEATURES — the panel-by-panel breakdown --}}
            @if($project->panels->isNotEmpty())
                <div>
                    <h2 class="font-display text-2xl font-bold text-slate-900">Panels &amp; features</h2>
                    <p class="mt-2 text-slate-600">A breakdown of each role/panel and what it can do.</p>
                    <div class="mt-6 space-y-5">
                        @foreach($project->panels as $panel)
                            <div class="rounded-2xl border border-slate-200 bg-white p-6">
                                <div class="flex items-center gap-3">
                                    <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-700">
                                        <x-icon :name="$panel->icon ?: 'layers'" class="h-5 w-5" />
                                    </div>
                                    <div>
                                        <h3 class="font-display text-lg font-bold text-slate-900">{{ $panel->name }}</h3>
                                        @if($panel->description)<p class="text-sm text-slate-500">{{ $panel->description }}</p>@endif
                                    </div>
                                </div>
                                @if($panel->features->isNotEmpty())
                                    <ul class="mt-4 grid gap-2 sm:grid-cols-2">
                                        @foreach($panel->features as $feature)
                                            <li class="flex items-start gap-2 text-sm text-slate-700">
                                                <x-icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" />
                                                <span>{{ $feature->title }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Gallery (lightGallery lightbox) --}}
            @if($project->images->isNotEmpty())
                <div>
                    <h2 class="font-display text-2xl font-bold text-slate-900">Screens</h2>
                    <p class="mt-1 text-sm text-slate-500">Click any image to open the gallery.</p>
                    <div data-lightgallery class="mt-5 grid gap-4 sm:grid-cols-2">
                        @foreach($project->images as $image)
                            <a href="{{ $image->url }}" data-sub-html="{{ $image->caption ?: $project->title }}"
                               class="group block cursor-zoom-in overflow-hidden rounded-xl border border-slate-200">
                                <x-picture :src="$image->url" :webp="$image->webp_url" :alt="$image->alt ?: $project->title" :width="$image->width" :height="$image->height" class="w-full transition duration-300 group-hover:scale-105" />
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- CTA --}}
    <section class="border-t border-slate-200 bg-slate-50">
        <div class="container-px py-12 text-center">
            <h2 class="font-display text-2xl font-bold text-slate-900">Want something like this?</h2>
            <p class="mx-auto mt-2 max-w-xl text-slate-600">I build production-grade systems end to end. Let's talk about your project.</p>
            <a href="{{ route('contact') }}" class="btn-primary mt-6">Start a project <x-icon name="arrow-right" class="h-4 w-4" /></a>
        </div>
    </section>

    {{-- Related --}}
    @if($related->isNotEmpty())
        <x-section eyebrow="More work" title="Related projects">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($related as $rel)
                    <x-card.project :project="$rel" />
                @endforeach
            </div>
        </x-section>
    @endif
@endsection
