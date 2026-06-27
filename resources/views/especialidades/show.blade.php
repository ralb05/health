<x-layouts.app :title="$specialty->name">
    <div class="flex flex-1 flex-col px-6 pt-8 pb-6">
        {{-- Volver --}}
        <a href="{{ route('inicio') }}" class="inline-flex items-center gap-1 text-sm font-medium text-navy-400 hover:text-navy-600">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Volver
        </a>

        {{-- Encabezado de la especialidad --}}
        <div class="mt-5 flex items-start justify-between gap-4">
            <div>
                <h1 class="text-2xl font-semibold text-navy-700">{{ $specialty->name }}</h1>
                <p class="mt-1 max-w-[15rem] text-sm text-navy-400">{{ $specialty->description }}</p>
            </div>
            <span class="inline-flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-lavender-100 text-navy-700">
                <x-logo size="h-9 w-9" />
            </span>
        </div>

        {{-- Atributos de confianza --}}
        <div class="mt-6 grid grid-cols-3 gap-2 text-center">
            @foreach ([
                ['Médicos especializados', 'M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0ZM4 21a8 8 0 0 1 16 0'],
                ['Atención confidencial', 'M12 3l7 3v5c0 4.2-2.9 7.5-7 9-4.1-1.5-7-4.8-7-9V6l7-3Z'],
                ['Consultas on-line', 'M15 10l4.5-2.5v9L15 14M4 7h9a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V8a1 1 0 0 1 1-1Z'],
            ] as [$label, $icon])
                <div class="flex flex-col items-center gap-1.5">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-white text-navy-600 ring-1 ring-navy-100">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $icon }}"/></svg>
                    </span>
                    <span class="text-[11px] leading-tight text-navy-400">{{ $label }}</span>
                </div>
            @endforeach
        </div>

        {{-- Lista de especialistas --}}
        <h2 class="mt-7 mb-3 font-semibold text-navy-700">Nuestros especialistas</h2>

        @if ($doctors->isEmpty())
            <p class="text-sm text-navy-400">Pronto tendremos especialistas disponibles en esta área.</p>
        @else
            <div class="space-y-3">
                @foreach ($doctors as $doctor)
                    <x-doctor-card :doctor="$doctor" />
                @endforeach
            </div>
        @endif
    </div>

    <x-slot:nav>
        <x-bottom-nav active="inicio" />
    </x-slot:nav>
</x-layouts.app>
