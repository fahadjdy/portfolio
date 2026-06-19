@php
    $siteName = settings('site_name', 'Fahad Jadiya');
    $tagline = settings('tagline', 'Senior Full-Stack Developer');
    $contactEmail = settings('contact_email', 'fahadjdy12@gmail.com');
@endphp
<footer class="border-t border-slate-200 bg-slate-50">
    <div class="container-px grid gap-8 py-12 md:grid-cols-4">
        <div class="md:col-span-2">
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-display text-lg font-bold text-slate-900">
                <span class="grid h-9 w-9 place-items-center rounded-lg bg-brand-600 text-sm font-bold text-white">
                    {{ \Illuminate\Support\Str::of($siteName)->explode(' ')->map(fn ($w) => \Illuminate\Support\Str::substr($w, 0, 1))->take(2)->implode('') }}
                </span>
                {{ $siteName }}
            </a>
            <p class="mt-3 max-w-sm text-sm text-slate-600">
                {{ $tagline }} — building scalable web apps, SaaS platforms, CRMs and AI-powered products with Laravel &amp; Vue.
            </p>

            @if(isset($socials) && $socials->isNotEmpty())
                <div class="mt-5 flex items-center gap-3">
                    @foreach($socials as $social)
                        <a href="{{ $social->url }}" target="_blank" rel="noopener noreferrer"
                           aria-label="{{ $social->label ?: $social->platform }}"
                           class="grid h-9 w-9 place-items-center rounded-lg border border-slate-200 bg-white text-slate-600 transition hover:border-brand-300 hover:text-brand-700">
                            <x-icon :name="$social->icon ?: 'globe'" class="h-4 w-4" />
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <h2 class="text-sm font-semibold text-slate-900">Explore</h2>
            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                <li><a class="hover:text-brand-700" href="{{ route('about') }}">About</a></li>
                <li><a class="hover:text-brand-700" href="{{ route('projects.index') }}">Projects</a></li>
                <li><a class="hover:text-brand-700" href="{{ route('services.index') }}">Services</a></li>
                <li><a class="hover:text-brand-700" href="{{ route('contact') }}">Contact</a></li>
            </ul>
        </div>

        <div>
            <h2 class="text-sm font-semibold text-slate-900">Get in touch</h2>
            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                <li><a class="hover:text-brand-700" href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></li>
                <li><a class="btn-primary mt-2 !px-4 !py-2 text-xs" href="{{ route('contact') }}">Start a project</a></li>
            </ul>
        </div>
    </div>

    <div class="border-t border-slate-200">
        <div class="container-px flex flex-col items-center justify-between gap-2 py-6 text-sm text-slate-500 sm:flex-row">
            <p>&copy; {{ now()->year }} {{ $siteName }}. All rights reserved.</p>
            <p>Built with Laravel &amp; Vue.</p>
        </div>
    </div>
</footer>
