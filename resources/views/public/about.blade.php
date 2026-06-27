@extends('layouts.app')

@section('title', 'About — Fahad Jadiya, Senior Full-Stack Developer')
@section('description', 'Learn about Fahad Jadiya — a Senior Full-Stack Developer with 7+ years building Laravel & Vue SaaS platforms, CRMs, management systems and AI-powered products.')
@section('canonical', route('about'))

@section('content')
    <header class="bg-gradient-to-b from-brand-50 to-white">
        <div class="container-px py-16">
            <div class="mx-auto max-w-3xl">
                <p class="eyebrow">{{ $about->subheading ?? 'Senior Full-Stack Developer' }}</p>
                <h1 class="mt-3 text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl">{{ $about->heading ?? 'About Fahad Jadiya' }}</h1>
                @if($about?->body)
                    <div class="speakable mt-6 space-y-4 text-lg leading-relaxed text-slate-600">
                        @foreach(preg_split('/\n\n+/', $about->body) as $para)
                            <p>{{ $para }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('contact') }}" class="btn-primary">Work with me <x-icon name="arrow-right" class="h-4 w-4" /></a>
                    <a href="{{ route('resume.download') }}" class="btn-ghost"><x-icon name="download" class="h-4 w-4" /> Download CV</a>
                </div>
            </div>
        </div>
    </header>

    @if($skillCategories->isNotEmpty())
        <x-section eyebrow="Toolbox" title="Skills & expertise">
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach($skillCategories as $category)
                    <div class="rounded-2xl border border-slate-200 bg-white p-6">
                        <div class="flex items-center gap-3">
                            <div class="grid h-10 w-10 place-items-center rounded-xl bg-brand-50 text-brand-700"><x-icon :name="$category->icon ?: 'layers'" class="h-5 w-5" /></div>
                            <h3 class="font-display text-base font-bold text-slate-900">{{ $category->name }}</h3>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-1.5">
                            @foreach($category->skills as $skill)
                                <x-badge>{{ $skill->name }}</x-badge>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </x-section>
    @endif

    @if($experiences->isNotEmpty())
        <x-section eyebrow="Career" title="Experience" class="bg-slate-50">
            <div class="relative mx-auto max-w-3xl border-l border-slate-200 pl-8">
                @foreach($experiences as $exp)
                    <div class="relative pb-10 last:pb-0">
                        <span class="absolute -left-[2.6rem] top-1 grid h-6 w-6 place-items-center rounded-full bg-brand-600 ring-4 ring-slate-50"><span class="h-2 w-2 rounded-full bg-white"></span></span>
                        <div class="text-sm font-medium text-brand-700">{{ $exp->start_date?->format('Y') }} — {{ $exp->is_current ? 'Present' : $exp->end_date?->format('Y') }}</div>
                        <h3 class="mt-1 font-display text-lg font-bold text-slate-900">{{ $exp->title }}</h3>
                        <p class="text-sm font-medium text-slate-600">{{ $exp->company }}@if($exp->location) · {{ $exp->location }}@endif</p>
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

    @if($education->isNotEmpty())
        <x-section eyebrow="Background" title="Education">
            <div class="mx-auto max-w-3xl space-y-4">
                @foreach($education as $edu)
                    <div class="rounded-2xl border border-slate-200 bg-white p-6">
                        <div class="text-sm font-medium text-brand-700">{{ $edu->start_year }} — {{ $edu->end_year ?: 'Present' }}</div>
                        <h3 class="mt-1 font-display text-lg font-bold text-slate-900">{{ $edu->degree }}@if($edu->field_of_study) · {{ $edu->field_of_study }}@endif</h3>
                        <p class="text-sm font-medium text-slate-600">{{ $edu->institution }}@if($edu->location) · {{ $edu->location }}@endif</p>
                        @if($edu->description)<p class="mt-2 text-sm text-slate-600">{{ $edu->description }}</p>@endif
                    </div>
                @endforeach
            </div>
        </x-section>
    @endif
@endsection
