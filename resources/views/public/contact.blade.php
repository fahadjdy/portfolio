@extends('layouts.app')

@section('title', 'Contact — Fahad Jadiya')
@section('description', "Get in touch with Fahad Jadiya to discuss your web app, SaaS platform, CRM or AI project. Typically replies within 1–2 business days.")

@section('content')
    <x-section eyebrow="Let's talk"
               title="Start a project"
               subtitle="Tell me what you're building and I'll get back within 1–2 business days.">
        <div class="grid gap-10 lg:grid-cols-5">
            <div class="lg:col-span-2">
                <div class="space-y-6">
                    <div class="flex items-start gap-3">
                        <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-700"><x-icon name="mail" class="h-5 w-5" /></div>
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Email</h2>
                            <a href="mailto:{{ settings('contact_email', 'fahadjdy12@gmail.com') }}" class="text-sm text-slate-600 hover:text-brand-700">{{ settings('contact_email', 'fahadjdy12@gmail.com') }}</a>
                        </div>
                    </div>
                    @if(settings('contact_phone'))
                        <div class="flex items-start gap-3">
                            <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-700"><x-icon name="phone" class="h-5 w-5" /></div>
                            <div>
                                <h2 class="text-sm font-semibold text-slate-900">Phone</h2>
                                <p class="text-sm text-slate-600">{{ settings('contact_phone') }}</p>
                            </div>
                        </div>
                    @endif
                    <div class="flex items-start gap-3">
                        <div class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-700"><x-icon name="map-pin" class="h-5 w-5" /></div>
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Location</h2>
                            <p class="text-sm text-slate-600">{{ settings('contact_address', 'India') }} · Available worldwide (remote)</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-3">
                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm sm:p-8">
                    @include('public.partials.contact-form', ['services' => $services])
                </div>
            </div>
        </div>
    </x-section>
@endsection
