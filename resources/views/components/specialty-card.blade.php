@props([
    'specialty',
])

@php
    // Iconos por especialidad (coinciden con el seeder: brain / chat)
    $icons = [
        'brain' => 'M12 4c-2 0-3 1.3-3 3-1.3 0-2.3 1-2.3 2.3 0 .7.3 1.3.8 1.7-.7.4-1.1 1.1-1.1 2 0 1.3 1 2.3 2.3 2.3.3 1.3 1.4 2.2 2.7 2.2.6 0 1-.1 1.5-.4M12 4c2 0 3 1.3 3 3 1.3 0 2.3 1 2.3 2.3 0 .7-.3 1.3-.8 1.7.7.4 1.1 1.1 1.1 2 0 1.3-1 2.3-2.3 2.3-.3 1.3-1.4 2.2-2.7 2.2-.6 0-1-.1-1.5-.4M12 4v14',
        'chat' => 'M4 5h16a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H9l-4 4v-4H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Z',
    ];
    $path = $icons[$specialty->icon] ?? $icons['brain'];
@endphp

<a href="{{ route('especialidades.show', $specialty) }}"
   class="flex flex-col rounded-2xl bg-white p-4 ring-1 ring-navy-100/70 shadow-sm transition hover:ring-navy-200 active:bg-navy-50">
    <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-lavender-100 text-navy-700">
        <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
            <path d="{{ $path }}"/>
        </svg>
    </span>

    <p class="mt-3 font-semibold text-navy-700">{{ $specialty->name }}</p>
    <p class="mt-1 line-clamp-2 text-sm text-navy-400">{{ $specialty->description }}</p>

    <span class="mt-3 inline-flex h-8 w-8 items-center justify-center self-end rounded-full bg-navy-700 text-white">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14M13 6l6 6-6 6"/>
        </svg>
    </span>
</a>
