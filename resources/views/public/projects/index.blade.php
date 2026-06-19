@extends('layouts.app')

@section('title', 'Projects & Case Studies — Fahad Jadiya')
@section('description', 'Explore production web apps, SaaS platforms, CRMs and AI integrations built by Fahad Jadiya — each with a detailed panel-by-panel feature breakdown.')

@section('content')
    <x-section eyebrow="Portfolio"
               title="Projects & case studies"
               subtitle="Real, production systems built with Laravel and Vue — explore each one panel by panel.">
        @if($techTags->isNotEmpty())
            <div class="-mt-4 mb-8 flex flex-wrap gap-2">
                <a href="{{ route('projects.index') }}"
                   @class(['rounded-full px-3.5 py-1.5 text-sm font-medium transition', 'bg-brand-600 text-white' => ! $activeTech, 'border border-slate-200 bg-white text-slate-600 hover:border-brand-300' => $activeTech])>
                    All
                </a>
                @foreach($techTags as $tag)
                    <a href="{{ route('projects.index', ['tech' => $tag->slug]) }}"
                       @class(['rounded-full px-3.5 py-1.5 text-sm font-medium transition', 'bg-brand-600 text-white' => $activeTech === $tag->slug, 'border border-slate-200 bg-white text-slate-600 hover:border-brand-300' => $activeTech !== $tag->slug])>
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif

        @if($projects->isNotEmpty())
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($projects as $project)
                    <x-card.project :project="$project" />
                @endforeach
            </div>
        @else
            <p class="rounded-2xl border border-dashed border-slate-300 p-10 text-center text-slate-500">No projects found for this filter.</p>
        @endif
    </x-section>
@endsection
