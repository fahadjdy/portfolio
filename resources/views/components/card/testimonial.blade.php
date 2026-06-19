@props(['testimonial'])

<figure class="flex h-full flex-col rounded-2xl border border-slate-200 bg-white p-6">
    @if($testimonial->rating)
        <div class="flex gap-0.5 text-amber-400" aria-label="{{ $testimonial->rating }} out of 5">
            @for($i = 0; $i < $testimonial->rating; $i++)
                <x-icon name="star" class="h-4 w-4" />
            @endfor
        </div>
    @endif

    <blockquote class="mt-3 flex-1 leading-relaxed text-slate-700">“{{ $testimonial->quote }}”</blockquote>

    <figcaption class="mt-5 flex items-center gap-3">
        @if($testimonial->avatar)
            <img src="{{ img_url($testimonial->avatar) }}" alt="{{ $testimonial->author_name }}" class="h-10 w-10 rounded-full object-cover" loading="lazy">
        @else
            <div class="grid h-10 w-10 place-items-center rounded-full bg-brand-100 font-semibold text-brand-700">
                {{ \Illuminate\Support\Str::substr($testimonial->author_name, 0, 1) }}
            </div>
        @endif
        <div>
            <div class="text-sm font-semibold text-slate-900">{{ $testimonial->author_name }}</div>
            <div class="text-xs text-slate-500">{{ $testimonial->author_title }}@if($testimonial->company), {{ $testimonial->company }}@endif</div>
        </div>
    </figcaption>
</figure>
