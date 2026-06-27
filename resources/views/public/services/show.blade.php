@extends('layouts.app')

@section('title', $service->title.' — Services | Fahad Jadiya')
@section('description', \Illuminate\Support\Str::limit($service->short_description, 155))
@section('canonical', route('services.show', $service->slug))

@section('content')
    <nav aria-label="Breadcrumb" class="border-b border-slate-100 bg-slate-50">
        <div class="container-px py-3">
            <ol class="flex flex-wrap items-center gap-1.5 text-sm text-slate-500">
                <li><a href="{{ route('home') }}" class="hover:text-brand-700">Home</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('services.index') }}" class="hover:text-brand-700">Services</a></li>
                <li aria-hidden="true">/</li>
                <li class="font-medium text-slate-700" aria-current="page">{{ $service->title }}</li>
            </ol>
        </div>
    </nav>

    <header class="bg-gradient-to-b from-brand-50 to-white">
        <div class="container-px py-14">
            <div class="mx-auto max-w-3xl">
                <div class="grid h-12 w-12 place-items-center rounded-xl bg-brand-600 text-white">
                    <x-icon :name="$service->icon ?: 'layers'" class="h-6 w-6" />
                </div>
                <h1 class="mt-4 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">{{ $service->title }}</h1>
                <p class="mt-3 text-lg text-slate-600">{{ $service->short_description }}</p>
            </div>
        </div>
    </header>

    <div class="container-px py-14">
        <div class="mx-auto max-w-3xl space-y-10">
            @if($service->description)
                <div class="prose prose-slate max-w-none text-slate-600">
                    <p class="text-lg leading-relaxed">{{ $service->description }}</p>
                </div>
            @endif

            @if(! empty($service->features))
                <div>
                    <h2 class="font-display text-2xl font-bold text-slate-900">What's included</h2>
                    <ul class="mt-5 grid gap-3 sm:grid-cols-2">
                        @foreach($service->features as $feature)
                            <li class="flex items-start gap-3 rounded-xl border border-slate-200 bg-white p-4 text-sm text-slate-700">
                                <x-icon name="check-circle" class="mt-0.5 h-5 w-5 shrink-0 text-brand-600" />
                                <span>{{ $feature }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($faqs->isNotEmpty())
                <div>
                    <h2 class="font-display text-2xl font-bold text-slate-900">FAQs</h2>
                    <div class="mt-5"><x-faq :items="$faqs" /></div>
                </div>
            @endif

            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center">
                <h2 class="font-display text-xl font-bold text-slate-900">Interested in {{ $service->title }}?</h2>
                <a href="{{ route('contact') }}" class="btn-primary mt-5">Start a conversation <x-icon name="arrow-right" class="h-4 w-4" /></a>
            </div>
        </div>
    </div>

    @if($services->isNotEmpty())
        <x-section eyebrow="Explore" title="Other services" class="bg-slate-50">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($services->take(3) as $other)
                    <x-card.service :service="$other" :link="true" />
                @endforeach
            </div>
        </x-section>
    @endif
@endsection
