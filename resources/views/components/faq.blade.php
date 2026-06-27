@props(['items' => []])

{{-- Semantic <details> accordion — crawlable for AEO, no JS needed. --}}
<div {{ $attributes->merge(['class' => 'divide-y divide-slate-200 overflow-hidden rounded-2xl border border-slate-200 bg-white']) }}>
    @foreach($items as $item)
        <details class="group p-5 sm:p-6">
            <summary class="flex cursor-pointer list-none items-center justify-between gap-4 text-base font-semibold text-slate-900 [&::-webkit-details-marker]:hidden">
                <span class="faq-q">{{ data_get($item, 'question') }}</span>
                <x-icon name="chevron-down" class="h-5 w-5 shrink-0 text-slate-400 transition group-open:rotate-180" />
            </summary>
            <div class="faq-a mt-3 leading-relaxed text-slate-600">{{ data_get($item, 'answer') }}</div>
        </details>
    @endforeach
</div>
