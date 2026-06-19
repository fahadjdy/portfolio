@extends('layouts.app')

@section('content')
    {{-- ===================== HERO ===================== --}}
    @php($heroData = $hero?->data ?? [])
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-brand-50 via-white to-white"></div>
        <div class="absolute -top-24 right-0 -z-10 h-72 w-72 rounded-full bg-brand-200/40 blur-3xl"></div>

        <div class="container-px py-20 sm:py-28">
            <div class="mx-auto max-w-3xl text-center">
                <p class="eyebrow">{{ $hero?->subheading ?? 'Senior Full-Stack Developer' }}</p>
                <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-slate-900 sm:text-6xl">
                    {{ $hero?->heading ?? 'I build fast, scalable web apps & SaaS platforms' }}
                </h1>
                <p class="mx-auto mt-6 max-w-2xl text-lg text-slate-600">
                    {{ $hero?->body ?? "I design and ship production-grade systems with Laravel, Vue and MySQL." }}
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <a href="{{ route('projects.index') }}" class="btn-primary">View my work <x-icon name="arrow-right" class="h-4 w-4" /></a>
                    <a href="{{ route('contact') }}" class="btn-ghost">Start a project</a>
                </div>

                @if(! empty($heroData['stats']))
                    <dl class="mx-auto mt-14 grid max-w-2xl grid-cols-3 gap-6">
                        @foreach($heroData['stats'] as $stat)
                            <div class="text-center">
                                <dt class="sr-only">{{ $stat['label'] }}</dt>
                                <dd class="font-display text-3xl font-extrabold text-brand-700">{{ $stat['value'] }}</dd>
                                <p class="mt-1 text-sm text-slate-500">{{ $stat['label'] }}</p>
                            </div>
                        @endforeach
                    </dl>
                @endif
            </div>
        </div>
    </section>

    {{-- ===================== ABOUT ===================== --}}
    @if($aboutIntro)
        <x-section id="about" eyebrow="About me" :title="$aboutIntro->heading">
            <div class="grid gap-8 lg:grid-cols-3">
                <div class="lg:col-span-2">
                    <p class="text-lg leading-relaxed text-slate-600">{{ $aboutIntro->body }}</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('about') }}" class="btn-ghost">More about me</a>
                        <a href="{{ route('resume.download') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-700">
                            <x-icon name="download" class="h-4 w-4" /> Download CV
                        </a>
                    </div>
                </div>
                @if($featuredTech->isNotEmpty())
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-6">
                        <h3 class="text-sm font-semibold text-slate-900">Core stack</h3>
                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach($featuredTech as $tag)
                                <x-badge>{{ $tag->name }}</x-badge>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </x-section>
    @endif

    {{-- ===================== SKILLS ===================== --}}
    @if($skillCategories->isNotEmpty())
        <x-section id="skills" eyebrow="What I work with" title="Skills & expertise" class="bg-slate-50">
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($skillCategories as $category)
                    <div class="rounded-2xl border border-slate-200 bg-white p-6">
                        <div class="flex items-center gap-3">
                            <div class="grid h-10 w-10 place-items-center rounded-xl bg-brand-50 text-brand-700">
                                <x-icon :name="$category->icon ?: 'layers'" class="h-5 w-5" />
                            </div>
                            <h3 class="font-display text-base font-bold text-slate-900">{{ $category->name }}</h3>
                        </div>
                        <ul class="mt-4 space-y-3">
                            @foreach($category->skills as $skill)
                                <li>
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="font-medium text-slate-700">{{ $skill->name }}</span>
                                        @if($skill->proficiency)<span class="text-xs text-slate-400">{{ $skill->proficiency }}%</span>@endif
                                    </div>
                                    @if($skill->proficiency)
                                        <div class="mt-1.5 h-1.5 w-full overflow-hidden rounded-full bg-slate-100">
                                            <div class="h-full rounded-full bg-brand-500" style="width: {{ $skill->proficiency }}%"></div>
                                        </div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </x-section>
    @endif

    {{-- ===================== EXPERIENCE ===================== --}}
    @if($experiences->isNotEmpty())
        <x-section id="experience" eyebrow="Career" title="Experience">
            <div class="relative border-l border-slate-200 pl-8">
                @foreach($experiences as $exp)
                    <div class="relative pb-10 last:pb-0">
                        <span class="absolute -left-[2.6rem] top-1 grid h-6 w-6 place-items-center rounded-full bg-brand-600 ring-4 ring-white">
                            <span class="h-2 w-2 rounded-full bg-white"></span>
                        </span>
                        <div class="flex flex-wrap items-center gap-x-2 text-sm text-slate-500">
                            <span class="font-medium text-brand-700">{{ $exp->start_date?->format('Y') }} — {{ $exp->is_current ? 'Present' : $exp->end_date?->format('Y') }}</span>
                            @if($exp->location)<span aria-hidden="true">·</span><span>{{ $exp->location }}</span>@endif
                        </div>
                        <h3 class="mt-1 font-display text-lg font-bold text-slate-900">{{ $exp->title }}</h3>
                        <p class="text-sm font-medium text-slate-600">{{ $exp->company }}</p>
                        @if($exp->description)<p class="mt-2 text-sm text-slate-600">{{ $exp->description }}</p>@endif
                        @if(! empty($exp->highlights))
                            <ul class="mt-3 space-y-1.5">
                                @foreach($exp->highlights as $h)
                                    <li class="flex items-start gap-2 text-sm text-slate-600"><x-icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" /><span>{{ $h }}</span></li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                @endforeach
            </div>
        </x-section>
    @endif

    {{-- ===================== SERVICES ===================== --}}
    @if($services->isNotEmpty())
        <x-section id="services" eyebrow="How I can help" title="Services" subtitle="From a single feature to a full platform — built end to end." class="bg-slate-50">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($services as $service)
                    <x-card.service :service="$service" :link="true" />
                @endforeach
            </div>
        </x-section>
    @endif

    {{-- ===================== FEATURED PROJECTS ===================== --}}
    @if($featuredProjects->isNotEmpty())
        <x-section id="projects" eyebrow="Selected work" title="Featured projects" subtitle="Real, production systems — each with a panel-by-panel breakdown.">
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($featuredProjects as $project)
                    <x-card.project :project="$project" />
                @endforeach
            </div>
            <div class="mt-10 text-center">
                <a href="{{ route('projects.index') }}" class="btn-ghost">View all projects <x-icon name="arrow-right" class="h-4 w-4" /></a>
            </div>
        </x-section>
    @endif

    {{-- ===================== TESTIMONIALS ===================== --}}
    @if($testimonials->isNotEmpty())
        <x-section id="testimonials" eyebrow="Kind words" title="What clients say" class="bg-slate-50">
            <div class="grid gap-6 md:grid-cols-3">
                @foreach($testimonials as $testimonial)
                    <x-card.testimonial :testimonial="$testimonial" />
                @endforeach
            </div>
        </x-section>
    @endif

    {{-- ===================== FAQ ===================== --}}
    @if($faqs->isNotEmpty())
        <x-section id="faq" eyebrow="Questions" title="Frequently asked questions" center>
            <div class="mx-auto max-w-3xl">
                <x-faq :items="$faqs" />
            </div>
        </x-section>
    @endif

    {{-- ===================== CONTACT ===================== --}}
    <x-section id="contact" eyebrow="Let's talk" title="Have a project in mind?" subtitle="Tell me about it and I'll get back within 1–2 business days.">
        <div class="mx-auto max-w-2xl rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
            @include('public.partials.contact-form', ['services' => $services])
        </div>
    </x-section>
@endsection
