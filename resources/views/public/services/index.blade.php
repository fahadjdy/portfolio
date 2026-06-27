@extends('layouts.app')

@section('title', 'Services — Fahad Jadiya')
@section('description', 'Full-stack development services by Fahad Jadiya: custom web apps, SaaS & management systems, CRMs, AI integrations, e-commerce, invoicing and high-performance websites.')
@section('canonical', route('services.index'))

@section('content')
    <x-section eyebrow="How I can help"
               title="Services"
               subtitle="From a single feature to a full platform — designed, built and deployed end to end.">
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($services as $service)
                <x-card.service :service="$service" :link="true" />
            @endforeach
        </div>

        <div class="mt-12 rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center">
            <h2 class="font-display text-2xl font-bold text-slate-900">Not sure what you need?</h2>
            <p class="mx-auto mt-2 max-w-xl text-slate-600">Tell me about your idea and I'll recommend the right approach and scope.</p>
            <a href="{{ route('contact') }}" class="btn-primary mt-6">Get in touch <x-icon name="arrow-right" class="h-4 w-4" /></a>
        </div>
    </x-section>
@endsection
