<x-layouts.panel title="Resumen" active="resumen">
    <x-slot:heading>Resumen</x-slot:heading>

    {{-- Métricas --}}
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-5">
        @foreach ([
            ['Especialidades', $stats['specialties']],
            ['Especialistas', $stats['doctors']],
            ['Citas confirmadas', $stats['confirmed']],
            ['Pendientes de pago', $stats['pending']],
            ['Ingresos', '$'.number_format($stats['revenue'], 0, ',', '.')],
        ] as [$label, $value])
            <div class="rounded-2xl bg-white p-4 ring-1 ring-navy-100/70 shadow-sm">
                <p class="text-xs text-navy-400">{{ $label }}</p>
                <p class="mt-1 text-2xl font-semibold text-navy-700">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    {{-- Próximas citas confirmadas --}}
    <div class="mt-8 rounded-2xl bg-white ring-1 ring-navy-100/70 shadow-sm">
        <div class="border-b border-navy-100 px-5 py-3">
            <h2 class="font-semibold text-navy-700">Próximas citas confirmadas</h2>
        </div>
        @if ($upcoming->isEmpty())
            <p class="px-5 py-6 text-sm text-navy-400">No hay citas confirmadas próximas.</p>
        @else
            <ul class="divide-y divide-navy-100">
                @foreach ($upcoming as $a)
                    <li class="flex items-center justify-between gap-4 px-5 py-3">
                        <div class="min-w-0">
                            <p class="truncate font-medium text-navy-700">{{ $a->patient->name }}</p>
                            <p class="text-sm text-navy-400">{{ $a->doctor->full_name }}</p>
                        </div>
                        <div class="text-right text-sm">
                            <p class="font-medium text-navy-600">{{ $a->starts_at->isoFormat('ddd D MMM') }}</p>
                            <p class="text-navy-400">{{ $a->starts_at->format('g:i a') }}</p>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-layouts.panel>
