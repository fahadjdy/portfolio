@extends('layouts.app')

@section('title', 'Fahad Jadiya — Senior Full-Stack Developer')
@section('description', 'Fahad Jadiya is a Senior Full-Stack Developer specializing in Laravel, Vue, MySQL and scalable web apps — building SaaS platforms, CRMs, management systems and AI integrations.')

@section('content')
    {{-- HERO (Phase 0 skeleton — full dynamic sections land in Phase 3) --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-gradient-to-b from-brand-50 via-white to-white"></div>
        <div class="absolute -top-24 right-0 -z-10 h-72 w-72 rounded-full bg-brand-200/40 blur-3xl"></div>

        <div class="container-px py-24 sm:py-32">
            <div class="mx-auto max-w-3xl text-center">
                <p class="eyebrow">Senior Full-Stack Developer</p>
                <h1 class="mt-4 text-4xl font-extrabold tracking-tight text-slate-900 sm:text-6xl">
                    I build fast, scalable web apps &amp; SaaS platforms
                </h1>
                <p class="mx-auto mt-6 max-w-2xl text-lg text-slate-600">
                    Hi, I'm <strong class="text-slate-900">Fahad Jadiya</strong> — I design and ship
                    production-grade systems with <strong>Laravel</strong>, <strong>Vue</strong> and
                    <strong>MySQL</strong>: CRMs, management platforms, AI integrations and high-performance
                    marketing sites.
                </p>
                <div class="mt-8 flex flex-wrap items-center justify-center gap-3">
                    <a href="/#projects" class="btn-primary">View my work</a>
                    <a href="/#contact" class="btn-ghost">Start a project</a>
                </div>
            </div>
        </div>
    </section>

    <section id="projects" class="section-anchor border-t border-slate-100 bg-slate-50">
        <div class="container-px py-20 text-center">
            <p class="eyebrow">Coming together</p>
            <h2 class="mt-3 text-3xl font-bold text-slate-900">Portfolio under active construction</h2>
            <p class="mx-auto mt-4 max-w-xl text-slate-600">
                The full dynamic experience — 9 in-depth project case studies, skills, experience and an
                admin-managed CMS — is being built phase by phase. This page confirms the deployment
                pipeline is live.
            </p>
        </div>
    </section>
@endsection
