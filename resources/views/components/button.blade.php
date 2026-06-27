@props([
    'href' => null,
    'variant' => 'primary', // primary | secondary
    'type' => 'button',
])

@php
    $base = 'inline-flex w-full items-center justify-center rounded-2xl px-6 py-4 text-base font-semibold transition focus:outline-none focus-visible:ring-2 focus-visible:ring-navy-400 focus-visible:ring-offset-2';

    $styles = [
        'primary' => 'bg-navy-700 text-white hover:bg-navy-800 active:bg-navy-900',
        'secondary' => 'border border-navy-200 bg-white text-navy-700 hover:bg-navy-50',
    ];

    $classes = $base . ' ' . ($styles[$variant] ?? $styles['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
