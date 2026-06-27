@props([
    'size' => 'h-16 w-16',
])

{{-- Marca Mind & Health: cerebro estilizado con hojas (bienestar mental) --}}
<svg {{ $attributes->merge(['class' => $size]) }} viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <defs>
        <linearGradient id="mh-grad" x1="0" y1="0" x2="64" y2="64" gradientUnits="userSpaceOnUse">
            <stop stop-color="#2bab9a"/>
            <stop offset="1" stop-color="#1e2a4a"/>
        </linearGradient>
    </defs>
    <path d="M32 8c-6 0-9 4-9 8-4 0-7 3-7 7 0 2 1 4 2 5-2 1-3 3-3 6 0 4 3 7 7 7 1 4 4 7 8 7 1 0 2-.3 3-.8V12.5C33 9.8 32.7 8 32 8Z"
          stroke="url(#mh-grad)" stroke-width="2.4" stroke-linejoin="round"/>
    <path d="M33 14c1-2 3-3 5-3 3 0 5 2 5 5 3 0 5 2 5 5 0 2-1 3-2 4 2 1 3 3 3 5 0 3-2 6-6 6-1 3-3 5-6 5"
          stroke="url(#mh-grad)" stroke-width="2.4" stroke-linejoin="round" stroke-linecap="round"/>
    <path d="M40 26c-3 1-5 3-6 6" stroke="#2bab9a" stroke-width="2.2" stroke-linecap="round"/>
    <path d="M26 22c2 1 3 3 3 5" stroke="#2bab9a" stroke-width="2.2" stroke-linecap="round"/>
</svg>
