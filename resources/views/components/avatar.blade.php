@props([
    'name' => '',
    'src' => null,
    'size' => 'h-14 w-14',
])

@php
    $initials = collect(explode(' ', preg_replace('/^(Dra?|Ps)\.?\s+/u', '', $name)))
        ->filter()
        ->take(2)
        ->map(fn ($w) => mb_strtoupper(mb_substr($w, 0, 1)))
        ->implode('');
@endphp

@if ($src)
    <img src="{{ $src }}" alt="{{ $name }}"
         {{ $attributes->merge(['class' => "$size rounded-full object-cover bg-navy-50"]) }}>
@else
    <span {{ $attributes->merge(['class' => "$size inline-flex items-center justify-center rounded-full bg-lavender-200 font-semibold text-navy-700"]) }}>
        {{ $initials }}
    </span>
@endif
