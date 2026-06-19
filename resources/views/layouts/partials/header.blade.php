@php
    $siteName = settings('site_name', 'Fahad Jadiya');
    $nav = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'About', 'route' => 'about'],
        ['label' => 'Projects', 'route' => 'projects.index'],
        ['label' => 'Services', 'route' => 'services.index'],
    ];
    if (settings('blog_enabled')) {
        $nav[] = ['label' => 'Blog', 'route' => 'blog.index'];
    }
    $nav[] = ['label' => 'Contact', 'route' => 'contact'];
@endphp
<header x-data="{ open: false, scrolled: false }"
        @scroll.window="scrolled = window.scrollY > 8"
        :class="scrolled ? 'border-slate-200 bg-white/90 shadow-sm backdrop-blur' : 'border-transparent bg-white'"
        class="sticky top-0 z-40 border-b transition-colors">
    <nav class="container-px flex h-16 items-center justify-between" aria-label="Primary">
        <a href="{{ route('home') }}" class="flex items-center gap-2 font-display text-lg font-bold text-slate-900">
            @if(setting_image('logo'))
                <img src="{{ setting_image('logo') }}" alt="{{ $siteName }}" class="h-9 w-auto">
            @else
                <span class="grid h-9 w-9 place-items-center rounded-lg bg-brand-600 text-sm font-bold text-white">
                    {{ \Illuminate\Support\Str::of($siteName)->explode(' ')->map(fn ($w) => \Illuminate\Support\Str::substr($w, 0, 1))->take(2)->implode('') }}
                </span>
            @endif
            <span class="whitespace-nowrap">{{ $siteName }}</span>
        </a>

        <div class="hidden items-center gap-8 md:flex">
            @foreach($nav as $item)
                <a href="{{ route($item['route']) }}"
                   @class([
                       'text-sm font-medium transition hover:text-brand-700',
                       'text-brand-700' => request()->routeIs($item['route']),
                       'text-slate-600' => ! request()->routeIs($item['route']),
                   ])>{{ $item['label'] }}</a>
            @endforeach
            <a href="{{ route('contact') }}" class="btn-primary">Hire me</a>
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
            @foreach($nav as $item)
                <a href="{{ route($item['route']) }}" @click="open = false"
                   class="rounded-lg px-3 py-2 text-base font-medium text-slate-700 hover:bg-slate-50">{{ $item['label'] }}</a>
            @endforeach
            <a href="{{ route('contact') }}" @click="open = false" class="btn-primary mt-2">Hire me</a>
        </div>
    </div>
</header>
