@props([
    'src',
    'webp' => null,
    'srcset' => null,
    'sizes' => '100vw',
    'alt' => '',
    'width' => null,
    'height' => null,
    'eager' => false,
])

{{-- Responsive <picture>: serves WebP first, falls back to the original.
     Explicit width/height avoid layout shift; lazy by default. --}}
<picture>
    @if($webp)
        <source srcset="{{ $srcset ?: $webp }}" sizes="{{ $sizes }}" type="image/webp">
    @endif
    <img
        src="{{ $src }}"
        alt="{{ $alt }}"
        loading="{{ $eager ? 'eager' : 'lazy' }}"
        decoding="async"
        @if($eager) fetchpriority="high" @endif
        @if($width) width="{{ $width }}" @endif
        @if($height) height="{{ $height }}" @endif
        {{ $attributes }}
    >
</picture>
