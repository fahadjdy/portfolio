@php
    // Static for the Phase 0 skeleton; becomes settings()/nav-driven in Phase 3.
    $nav = [
        ['label' => 'About', 'href' => '/#about'],
        ['label' => 'Skills', 'href' => '/#skills'],
        ['label' => 'Projects', 'href' => '/#projects'],
        ['label' => 'Services', 'href' => '/#services'],
        ['label' => 'Contact', 'href' => '/#contact'],
    ];
@endphp
<header x-data="{ open: false, scrolled: false }"
        @scroll.window="scrolled = window.scrollY > 8"
        :class="scrolled ? 'border-slate-200 bg-white/90 shadow-sm backdrop-blur' : 'border-transparent bg-white'"
        class="sticky top-0 z-40 border-b transition-colors">
    <nav class="container-px flex h-16 items-center justify-between" aria-label="Primary">
        <a href="/" class="flex items-center gap-2 font-display text-lg font-bold text-slate-900">
            <span class="grid h-9 w-9 place-items-center rounded-lg bg-brand-600 text-sm font-bold text-white">FJ</span>
            Fahad&nbsp;Jadiya
        </a>

        <div class="hidden items-center gap-8 md:flex">
            @foreach ($nav as $item)
                <a href="{{ $item['href'] }}" class="text-sm font-medium text-slate-600 transition hover:text-brand-700">{{ $item['label'] }}</a>
            @endforeach
            <a href="/#contact" class="btn-primary">Hire me</a>
        </div>

        <button type="button" class="inline-flex items-center justify-center rounded-lg p-2 text-slate-700 md:hidden"
                @click="open = !open" :aria-expanded="open" aria-controls="mobile-menu" aria-label="Toggle menu">
            <svg x-show="!open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <svg x-show="open" x-cloak class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    </nav>

    <div id="mobile-menu" x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-150" x-transition:enter-start="-translate-y-2 opacity-0" x-transition:enter-end="translate-y-0 opacity-100"
         class="border-t border-slate-200 md:hidden">
        <div class="container-px flex flex-col gap-1 py-3">
            @foreach ($nav as $item)
                <a href="{{ $item['href'] }}" @click="open = false" class="rounded-lg px-3 py-2 text-base font-medium text-slate-700 hover:bg-slate-50">{{ $item['label'] }}</a>
            @endforeach
            <a href="/#contact" @click="open = false" class="btn-primary mt-2">Hire me</a>
        </div>
    </div>
</header>
