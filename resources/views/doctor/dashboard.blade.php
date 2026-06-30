<x-layouts.panel title="Mi agenda" active="agenda">
    <x-slot:heading>Hola, {{ \Illuminate\Support\Str::of($doctor->full_name)->after(' ') }}</x-slot:heading>

    {{-- Próximas --}}
    <div class="rounded-2xl bg-white ring-1 ring-navy-100/70 shadow-sm">
        <div class="border-b border-navy-100 px-5 py-3">
            <h2 class="font-semibold text-navy-700">Próximas consultas</h2>
        </div>

        @if ($upcoming->isEmpty())
            <p class="px-5 py-6 text-sm text-navy-400">No tienes consultas próximas.</p>
        @else
            <ul class="divide-y divide-navy-100">
                @foreach ($upcoming as $a)
                    <li class="px-5 py-4">
                        <div class="flex flex-wrap items-start justify-between gap-3">
                            <div>
                                <p class="font-medium text-navy-700">{{ $a->patient->name }}</p>
                                <p class="text-sm text-navy-400">
                                    {{ $a->starts_at->isoFormat('dddd D [de] MMMM') }} · {{ $a->starts_at->format('g:i a') }}
                                </p>
                                @if ($a->patient->phone)
                                    <p class="text-xs text-navy-300">Tel: {{ $a->patient->phone }}</p>
                                @endif
                            </div>
                            <x-status-badge :status="$a->status" :label="$a->status_label" />
                        </div>

                        {{-- Enlace de la videollamada --}}
                        <form method="POST" action="{{ route('doctor.citas.meeting', $a) }}" class="mt-3 flex flex-wrap items-center gap-2">
                            @csrf
                            <input type="url" name="meeting_url" value="{{ $a->meeting_url }}"
                                   placeholder="Pega el enlace de la videollamada (Meet, Zoom...)"
                                   class="min-w-0 flex-1 rounded-xl border border-navy-200 bg-white px-3 py-2 text-sm focus:border-navy-400 focus:ring-2 focus:ring-navy-100 focus:outline-none">
                            <button class="rounded-xl bg-navy-700 px-4 py-2 text-sm font-semibold text-white hover:bg-navy-800">Guardar enlace</button>
                        </form>

                        <form method="POST" action="{{ route('doctor.citas.complete', $a) }}" class="mt-2">
                            @csrf
                            <button class="text-sm font-medium text-navy-400 hover:text-navy-700">Marcar como completada</button>
                        </form>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    {{-- Pasadas --}}
    @if ($past->isNotEmpty())
        <h2 class="mt-8 mb-3 font-semibold text-navy-700">Consultas pasadas</h2>
        <div class="overflow-hidden rounded-2xl bg-white ring-1 ring-navy-100/70 shadow-sm">
            <ul class="divide-y divide-navy-100">
                @foreach ($past as $a)
                    <li class="flex items-center justify-between gap-4 px-5 py-3">
                        <div>
                            <p class="font-medium text-navy-700">{{ $a->patient->name }}</p>
                            <p class="text-sm text-navy-400">{{ $a->starts_at->isoFormat('D MMM YYYY') }} · {{ $a->starts_at->format('g:i a') }}</p>
                        </div>
                        <x-status-badge :status="$a->status" :label="$a->status_label" />
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
</x-layouts.panel>
