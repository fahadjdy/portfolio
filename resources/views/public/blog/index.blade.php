@extends('layouts.app')

@section('title', 'Blog — Fahad Jadiya')
@section('description', 'Articles on Laravel, Vue, SaaS architecture, AI integration and full-stack engineering by Fahad Jadiya.')

@section('content')
    <x-section eyebrow="Writing"
               title="Blog"
               subtitle="Notes on Laravel, Vue, SaaS architecture and AI — from real production work.">
        @if($posts->count())
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($posts as $post)
                    <x-card.post :post="$post" />
                @endforeach
            </div>

            @if($posts->hasPages())
                <div class="mt-10">
                    {{ $posts->links() }}
                </div>
            @endif
        @else
            <p class="rounded-2xl border border-dashed border-slate-300 p-10 text-center text-slate-500">No articles published yet — check back soon.</p>
        @endif
    </x-section>
@endsection
