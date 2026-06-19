@props(['post'])

<article class="group flex h-full flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition duration-300 hover:-translate-y-1 hover:border-brand-200 hover:shadow-xl hover:shadow-slate-200/60">
    <a href="{{ route('blog.show', $post) }}" class="relative block aspect-[16/10] overflow-hidden bg-slate-100">
        @if($post->cover_image)
            <x-picture :src="img_url($post->cover_image)" :alt="$post->title" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" />
        @else
            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-500 via-brand-600 to-brand-800 p-6 text-center">
                <span class="font-display text-base font-bold leading-snug text-white/95">{{ $post->title }}</span>
            </div>
        @endif
        @if($post->category)
            <span class="absolute left-3 top-3 rounded-full bg-white/95 px-2.5 py-1 text-[11px] font-semibold text-brand-700 shadow-sm backdrop-blur">{{ $post->category->name }}</span>
        @endif
    </a>

    <div class="flex flex-1 flex-col p-5">
        <div class="flex items-center gap-2 text-xs text-slate-400">
            <time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('d M Y') }}</time>
            @if($post->reading_minutes)<span aria-hidden="true">·</span><span>{{ $post->reading_minutes }} min read</span>@endif
        </div>
        <h3 class="mt-2 font-display text-lg font-bold leading-snug text-slate-900 transition group-hover:text-brand-700">
            <a href="{{ route('blog.show', $post) }}" class="line-clamp-2">{{ $post->title }}</a>
        </h3>
        @if($post->excerpt)<p class="mt-2 line-clamp-2 flex-1 text-sm text-slate-600">{{ $post->excerpt }}</p>@endif
        <span class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-700">
            Read article <x-icon name="arrow-right" class="h-4 w-4 transition group-hover:translate-x-1" />
        </span>
    </div>
</article>
