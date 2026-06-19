@props(['project'])

<a href="{{ route('projects.show', $project) }}"
   class="group flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white transition hover:-translate-y-0.5 hover:shadow-lg">
    <div class="aspect-[16/10] overflow-hidden bg-slate-100">
        @if($project->cover_image)
            <x-picture :src="img_url($project->cover_image)" :alt="$project->title"
                       class="h-full w-full object-cover transition duration-500 group-hover:scale-105" />
        @else
            <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-brand-500 via-brand-600 to-brand-800 p-6 text-center">
                <span class="font-display text-lg font-bold leading-snug text-white">{{ $project->title }}</span>
            </div>
        @endif
    </div>

    <div class="flex flex-1 flex-col p-5">
        <div class="flex items-center gap-2 text-xs font-medium text-slate-500">
            <span>{{ $project->category }}</span>
            @if($project->year)<span aria-hidden="true">·</span><span>{{ $project->year }}</span>@endif
        </div>
        <h3 class="mt-2 font-display text-lg font-bold text-slate-900 transition group-hover:text-brand-700">{{ $project->title }}</h3>
        <p class="mt-2 line-clamp-3 text-sm text-slate-600">{{ $project->summary }}</p>

        @if($project->techTags->isNotEmpty())
            <div class="mt-4 flex flex-wrap gap-1.5">
                @foreach($project->techTags->take(4) as $tag)
                    <x-badge>{{ $tag->name }}</x-badge>
                @endforeach
            </div>
        @endif

        <span class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-700">
            View case study
            <x-icon name="arrow-right" class="h-4 w-4 transition group-hover:translate-x-0.5" />
        </span>
    </div>
</a>
