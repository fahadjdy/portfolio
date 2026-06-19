@props(['id' => null, 'eyebrow' => null, 'title' => null, 'subtitle' => null, 'center' => false])

<section @if($id) id="{{ $id }}" @endif {{ $attributes->merge(['class' => 'section-anchor py-20 sm:py-24']) }}>
    <div class="container-px">
        @if($eyebrow || $title || $subtitle)
            <div class="{{ $center ? 'mx-auto max-w-2xl text-center' : 'max-w-2xl' }}">
                @if($eyebrow)<p class="eyebrow">{{ $eyebrow }}</p>@endif
                @if($title)<h2 class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">{{ $title }}</h2>@endif
                @if($subtitle)<p class="mt-4 text-lg text-slate-600">{{ $subtitle }}</p>@endif
            </div>
        @endif

        <div @class(['mt-12' => $eyebrow || $title || $subtitle])>
            {{ $slot }}
        </div>
    </div>
</section>
