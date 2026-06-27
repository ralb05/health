@props([
    'active' => 'inicio', // inicio | citas | mensajes | perfil
])

@php
    // Rutas reales se conectan en entregables posteriores; por ahora placeholders.
    $items = [
        'inicio'   => ['label' => 'Inicio',   'href' => url('/inicio')],
        'citas'    => ['label' => 'Citas',    'href' => '#'],
        'mensajes' => ['label' => 'Mensajes', 'href' => '#'],
        'perfil'   => ['label' => 'Perfil',   'href' => '#'],
    ];

    $icons = [
        'inicio'   => 'M3 11.5 12 4l9 7.5M5 10v9a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-9',
        'citas'    => 'M7 3v3m10-3v3M4 9h16M5 6h14a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1Z',
        'mensajes' => 'M4 5h16a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H9l-4 4v-4H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z',
        'perfil'   => 'M12 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm-7 8a7 7 0 0 1 14 0',
    ];
@endphp

<nav class="sticky bottom-0 z-10 border-t border-navy-100 bg-white/95 backdrop-blur">
    <ul class="grid grid-cols-4">
        @foreach ($items as $key => $item)
            @php $isActive = $active === $key; @endphp
            <li>
                <a href="{{ $item['href'] }}"
                   @class([
                       'flex flex-col items-center gap-1 py-2.5 text-xs font-medium transition',
                       'text-navy-700' => $isActive,
                       'text-navy-300 hover:text-navy-500' => ! $isActive,
                   ])>
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="{{ $isActive ? '2.2' : '1.8' }}"
                         stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="{{ $icons[$key] }}"/>
                    </svg>
                    <span>{{ $item['label'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>
