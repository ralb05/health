<x-layouts.panel title="Disponibilidad" active="disponibilidad">
    <x-slot:heading>Mi disponibilidad</x-slot:heading>

    <div class="grid gap-6 lg:grid-cols-3">
        {{-- Horarios actuales --}}
        <div class="lg:col-span-2">
            <div class="rounded-2xl bg-white ring-1 ring-navy-100/70 shadow-sm">
                <div class="border-b border-navy-100 px-5 py-3">
                    <h2 class="font-semibold text-navy-700">Horarios de atención</h2>
                    <p class="text-xs text-navy-400">Los cupos disponibles para los pacientes se calculan a partir de estos bloques.</p>
                </div>

                @php $hasAny = collect($weekdays)->keys()->contains(fn ($w) => isset($schedules[$w])); @endphp
                @if (! $hasAny)
                    <p class="px-5 py-6 text-sm text-navy-400">Aún no has definido horarios. Agrega uno con el formulario de la derecha.</p>
                @else
                    <ul class="divide-y divide-navy-100">
                        @foreach ($weekdays as $num => $name)
                            @if (isset($schedules[$num]))
                                <li class="px-5 py-3">
                                    <p class="text-sm font-semibold text-navy-700">{{ $name }}</p>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach ($schedules[$num] as $s)
                                            <span class="inline-flex items-center gap-2 rounded-lg bg-navy-50 px-3 py-1 text-sm text-navy-600">
                                                {{ \Illuminate\Support\Str::of($s->start_time)->substr(0,5) }}–{{ \Illuminate\Support\Str::of($s->end_time)->substr(0,5) }}
                                                <span class="text-xs text-navy-300">({{ $s->slot_minutes }}m)</span>
                                                <form method="POST" action="{{ route('doctor.schedules.destroy', $s) }}" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button class="text-navy-300 hover:text-red-500" title="Eliminar">&times;</button>
                                                </form>
                                            </span>
                                        @endforeach
                                    </div>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- Agregar horario --}}
        <div>
            <form method="POST" action="{{ route('doctor.schedules.store') }}" class="space-y-4 rounded-2xl bg-white p-5 ring-1 ring-navy-100/70 shadow-sm">
                @csrf
                <h2 class="font-semibold text-navy-700">Agregar horario</h2>

                <div class="space-y-1.5">
                    <label for="weekday" class="block text-sm font-medium text-navy-700">Día</label>
                    <select id="weekday" name="weekday" class="w-full rounded-xl border border-navy-200 bg-white px-4 py-3 text-navy-900 focus:border-navy-400 focus:ring-2 focus:ring-navy-100 focus:outline-none">
                        @foreach ($weekdays as $num => $name)
                            <option value="{{ $num }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <x-field label="Desde" name="start_time" type="time" value="08:00" required />
                    <x-field label="Hasta" name="end_time" type="time" value="12:00" required />
                </div>

                <div class="space-y-1.5">
                    <label for="slot_minutes" class="block text-sm font-medium text-navy-700">Duración del cupo</label>
                    <select id="slot_minutes" name="slot_minutes" class="w-full rounded-xl border border-navy-200 bg-white px-4 py-3 text-navy-900 focus:border-navy-400 focus:ring-2 focus:ring-navy-100 focus:outline-none">
                        @foreach ([30,45,60,90] as $m)
                            <option value="{{ $m }}" @selected($m===60)>{{ $m }} minutos</option>
                        @endforeach
                    </select>
                </div>

                <x-button type="submit">Agregar</x-button>
            </form>
        </div>
    </div>
</x-layouts.panel>
