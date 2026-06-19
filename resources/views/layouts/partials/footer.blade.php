@php
    // Static for Phase 0; social links + contact become DB-driven in Phase 3.
    $year = now()->year;
@endphp
<footer class="border-t border-slate-200 bg-slate-50">
    <div class="container-px grid gap-8 py-12 md:grid-cols-3">
        <div>
            <a href="/" class="flex items-center gap-2 font-display text-lg font-bold text-slate-900">
                <span class="grid h-9 w-9 place-items-center rounded-lg bg-brand-600 text-sm font-bold text-white">FJ</span>
                Fahad Jadiya
            </a>
            <p class="mt-3 max-w-xs text-sm text-slate-600">
                Senior Full-Stack Developer building scalable web apps, SaaS platforms, CRMs and AI-powered products.
            </p>
        </div>

        <div>
            <h2 class="text-sm font-semibold text-slate-900">Explore</h2>
            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                <li><a class="hover:text-brand-700" href="/#about">About</a></li>
                <li><a class="hover:text-brand-700" href="/#projects">Projects</a></li>
                <li><a class="hover:text-brand-700" href="/#services">Services</a></li>
                <li><a class="hover:text-brand-700" href="/#contact">Contact</a></li>
            </ul>
        </div>

        <div>
            <h2 class="text-sm font-semibold text-slate-900">Get in touch</h2>
            <ul class="mt-3 space-y-2 text-sm text-slate-600">
                <li><a class="hover:text-brand-700" href="mailto:fahadjdy12@gmail.com">fahadjdy12@gmail.com</a></li>
                <li><a class="hover:text-brand-700" href="https://fahad-jadiya.com">fahad-jadiya.com</a></li>
            </ul>
        </div>
    </div>

    <div class="border-t border-slate-200">
        <div class="container-px flex flex-col items-center justify-between gap-2 py-6 text-sm text-slate-500 sm:flex-row">
            <p>&copy; {{ $year }} Fahad Jadiya. All rights reserved.</p>
            <p>Built with Laravel &amp; Vue.</p>
        </div>
    </div>
</footer>
