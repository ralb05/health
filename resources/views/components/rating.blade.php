@props([
    'value' => null,
    'count' => null,
])

@if ($value)
    <span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1 text-sm text-navy-500']) }}>
        <svg class="h-4 w-4 text-amber-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path d="M10 1.5l2.6 5.3 5.9.9-4.3 4.1 1 5.8L10 15l-5.2 2.6 1-5.8L1.5 7.7l5.9-.9L10 1.5z"/>
        </svg>
        <span class="font-semibold text-navy-700">{{ number_format($value, 1) }}</span>
        @if ($count)
            <span class="text-navy-300">· {{ $count }} reseñas</span>
        @endif
    </span>
@endif
