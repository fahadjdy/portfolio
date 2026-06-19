@props(['service', 'link' => false])

<div {{ $attributes->merge(['class' => 'flex flex-col rounded-2xl border border-slate-200 bg-white p-6 transition hover:border-brand-200 hover:shadow-md']) }}>
    <div class="grid h-11 w-11 place-items-center rounded-xl bg-brand-50 text-brand-700">
        <x-icon :name="$service->icon ?: 'layers'" class="h-5 w-5" />
    </div>
    <h3 class="mt-4 font-display text-lg font-bold text-slate-900">{{ $service->title }}</h3>
    <p class="mt-2 flex-1 text-sm text-slate-600">{{ $service->short_description }}</p>

    @if(! empty($service->features))
        <ul class="mt-4 space-y-1.5">
            @foreach(array_slice($service->features, 0, 4) as $feature)
                <li class="flex items-start gap-2 text-sm text-slate-600">
                    <x-icon name="check" class="mt-0.5 h-4 w-4 shrink-0 text-brand-600" />
                    <span>{{ $feature }}</span>
                </li>
            @endforeach
        </ul>
    @endif

    @if($link)
        <a href="{{ route('services.show', $service) }}" class="mt-5 inline-flex items-center gap-1.5 text-sm font-semibold text-brand-700">
            Learn more <x-icon name="arrow-right" class="h-4 w-4" />
        </a>
    @endif
</div>
