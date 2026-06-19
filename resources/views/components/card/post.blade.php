@props(['post'])

<article class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-0.5 hover:shadow-lg">
    <a href="{{ route('blog.show', $post) }}" class="block aspect-[16/9] overflow-hidden bg-slate-100">
        @if($post->cover_image)
            <x-picture :src="img_url($post->cover_image)" :alt="$post->title" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" />
        @else
            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-500 to-brand-800 p-6 text-center">
                <span class="font-display text-base font-bold leading-snug text-white">{{ $post->title }}</span>
            </div>
        @endif
    </a>
    <div class="flex flex-1 flex-col p-5">
        <div class="flex flex-wrap items-center gap-2 text-xs font-medium text-slate-500">
            @if($post->category)<span class="text-brand-700">{{ $post->category->name }}</span><span aria-hidden="true">·</span>@endif
            <time :datetime="$post->published_at?->toDateString()">{{ $post->published_at?->format('d M Y') }}</time>
            @if($post->reading_minutes)<span aria-hidden="true">·</span><span>{{ $post->reading_minutes }} min read</span>@endif
        </div>
        <h3 class="mt-2 font-display text-lg font-bold text-slate-900 transition group-hover:text-brand-700">
            <a href="{{ route('blog.show', $post) }}">{{ $post->title }}</a>
        </h3>
        @if($post->excerpt)<p class="mt-2 line-clamp-3 text-sm text-slate-600">{{ $post->excerpt }}</p>@endif
        <a href="{{ route('blog.show', $post) }}" class="mt-4 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-700">
            Read article <x-icon name="arrow-right" class="h-4 w-4 transition group-hover:translate-x-0.5" />
        </a>
    </div>
</article>
