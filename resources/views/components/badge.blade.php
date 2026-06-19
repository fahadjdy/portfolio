@props(['href' => null])

@php
    $base = 'inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-medium text-slate-600';
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $base.' transition hover:border-brand-300 hover:text-brand-700']) }}>{{ $slot }}</a>
@else
    <span {{ $attributes->merge(['class' => $base]) }}>{{ $slot }}</span>
@endif
