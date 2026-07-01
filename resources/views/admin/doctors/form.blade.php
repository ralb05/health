@php
    $editing = $doctor->exists;
    $tagsValue = old('tags', is_array($doctor->tags) ? implode(', ', $doctor->tags) : '');
@endphp

<x-layouts.panel :title="$editing ? 'Editar especialista' : 'Nuevo especialista'" active="especialistas">
    <x-slot:heading>{{ $editing ? 'Editar especialista' : 'Nuevo especialista' }}</x-slot:heading>

    <form method="POST"
          action="{{ $editing ? route('admin.doctors.update', $doctor) : route('admin.doctors.store') }}"
          class="max-w-2xl space-y-5 rounded-2xl bg-white p-6 ring-1 ring-navy-100/70 shadow-sm">
        @csrf
        @if ($editing) @method('PUT') @endif

        <div class="grid gap-4 sm:grid-cols-2">
            <x-field label="Nombre completo" name="full_name" :value="$doctor->full_name" required />
            <x-field label="Título" name="title" :value="$doctor->title" placeholder="Psiquiatra" />
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div class="space-y-1.5">
                <label for="specialty_id" class="block text-sm font-medium text-navy-700">Especialidad</label>
                <select id="specialty_id" name="specialty_id" required
                        class="w-full rounded-xl border border-navy-200 bg-white px-4 py-3 text-navy-900 focus:border-navy-400 focus:ring-2 focus:ring-navy-100 focus:outline-none">
                    @foreach ($specialties as $sp)
                        <option value="{{ $sp->id }}" @selected(old('specialty_id', $doctor->specialty_id) == $sp->id)>{{ $sp->name }}</option>
                    @endforeach
                </select>
            </div>
            <x-field label="Precio consulta (COP)" name="price_cop" type="number" :value="$doctor->price_cop" required placeholder="120000" />
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <x-field label="Años de experiencia" name="experience_years" type="number" :value="$doctor->experience_years" required />
            <x-field label="Calificación (0-5)" name="rating" type="number" :value="$doctor->rating" placeholder="5.0" />
            <x-field label="N.º reseñas" name="reviews_count" type="number" :value="$doctor->reviews_count" />
        </div>

        <x-field label="Foto (URL)" name="photo_url" type="url" :value="$doctor->photo_url" placeholder="https://..." />
        <x-field label="Etiquetas (separadas por coma)" name="tags" :value="$tagsValue" placeholder="Adultos, Adolescentes" />

        <div class="space-y-1.5">
            <label for="bio" class="block text-sm font-medium text-navy-700">Biografía</label>
            <textarea id="bio" name="bio" rows="3"
                      class="w-full rounded-xl border border-navy-200 bg-white px-4 py-3 text-navy-900 focus:border-navy-400 focus:ring-2 focus:ring-navy-100 focus:outline-none">{{ old('bio', $doctor->bio) }}</textarea>
        </div>

        <label class="flex items-center gap-2 text-sm text-navy-600">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $doctor->is_active ?? true))
                   class="h-4 w-4 rounded border-navy-200 text-navy-700 focus:ring-navy-300">
            Activo (visible para pacientes)
        </label>

        {{-- Acceso (login) del especialista --}}
        <div class="rounded-xl bg-navy-50 p-4">
            <p class="text-sm font-semibold text-navy-700">Acceso del especialista (opcional)</p>
            <p class="mt-0.5 text-xs text-navy-400">Crea un usuario para que el especialista entre a su panel. Deja en blanco si no aplica.</p>
            <div class="mt-3 grid gap-4 sm:grid-cols-2">
                <x-field label="Correo de acceso" name="login_email" type="email" :value="$doctor->user?->email" />
                <x-field label="Contraseña" name="login_password" type="password" :placeholder="$editing && $doctor->user ? 'Dejar en blanco = sin cambios' : 'Mínimo 8 caracteres'" />
            </div>
        </div>

        <div class="flex gap-3 pt-1">
            <x-button type="submit" class="!w-auto px-6">Guardar</x-button>
            <a href="{{ route('admin.doctors.index') }}" class="px-4 py-3 text-sm font-medium text-navy-400 hover:text-navy-700">Cancelar</a>
        </div>
    </form>

    {{-- Horarios de atención (disponibilidad) — solo al editar --}}
    @if ($editing)
        @php
            $byWeekday = $doctor->schedules->groupBy('weekday');
        @endphp
        <div class="mt-6 max-w-2xl rounded-2xl bg-white p-6 ring-1 ring-navy-100/70 shadow-sm">
            <h2 class="font-semibold text-navy-700">Horarios de atención</h2>
            <p class="mt-0.5 text-sm text-navy-400">Los cupos que verán los pacientes se calculan a partir de estos bloques.</p>

            {{-- Bloques actuales --}}
            <div class="mt-4 space-y-2">
                @php $hasAny = collect($weekdays)->keys()->contains(fn ($w) => isset($byWeekday[$w])); @endphp
                @if (! $hasAny)
                    <p class="text-sm text-navy-400">Aún no hay horarios. Agrega uno abajo para que este especialista aparezca con cupos.</p>
                @else
                    @foreach ($weekdays as $num => $name)
                        @isset($byWeekday[$num])
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="w-24 text-sm font-medium text-navy-600">{{ $name }}</span>
                                @foreach ($byWeekday[$num] as $s)
                                    <span class="inline-flex items-center gap-2 rounded-lg bg-navy-50 px-3 py-1 text-sm text-navy-600">
                                        {{ \Illuminate\Support\Str::of($s->start_time)->substr(0,5) }}–{{ \Illuminate\Support\Str::of($s->end_time)->substr(0,5) }}
                                        <span class="text-xs text-navy-300">({{ $s->slot_minutes }}m)</span>
                                        <form method="POST" action="{{ route('admin.doctors.schedules.destroy', [$doctor, $s]) }}" class="inline">
                                            @csrf @method('DELETE')
                                            <button class="text-navy-300 hover:text-red-500" title="Eliminar">&times;</button>
                                        </form>
                                    </span>
                                @endforeach
                            </div>
                        @endisset
                    @endforeach
                @endif
            </div>

            {{-- Agregar horario --}}
            <form method="POST" action="{{ route('admin.doctors.schedules.store', $doctor) }}" class="mt-5 flex flex-wrap items-end gap-3 border-t border-navy-100 pt-4">
                @csrf
                <div class="space-y-1">
                    <label class="block text-xs font-medium text-navy-500">Día</label>
                    <select name="weekday" class="rounded-xl border border-navy-200 bg-white px-3 py-2 text-sm focus:border-navy-400 focus:ring-2 focus:ring-navy-100 focus:outline-none">
                        @foreach ($weekdays as $num => $name)
                            <option value="{{ $num }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-1">
                    <label class="block text-xs font-medium text-navy-500">Desde</label>
                    <input type="time" name="start_time" value="08:00" required class="rounded-xl border border-navy-200 bg-white px-3 py-2 text-sm focus:border-navy-400 focus:ring-2 focus:ring-navy-100 focus:outline-none">
                </div>
                <div class="space-y-1">
                    <label class="block text-xs font-medium text-navy-500">Hasta</label>
                    <input type="time" name="end_time" value="12:00" required class="rounded-xl border border-navy-200 bg-white px-3 py-2 text-sm focus:border-navy-400 focus:ring-2 focus:ring-navy-100 focus:outline-none">
                </div>
                <div class="space-y-1">
                    <label class="block text-xs font-medium text-navy-500">Cupo</label>
                    <select name="slot_minutes" class="rounded-xl border border-navy-200 bg-white px-3 py-2 text-sm focus:border-navy-400 focus:ring-2 focus:ring-navy-100 focus:outline-none">
                        @foreach ([30,45,60,90] as $m)
                            <option value="{{ $m }}" @selected($m===60)>{{ $m }} min</option>
                        @endforeach
                    </select>
                </div>
                <button class="rounded-xl bg-navy-700 px-5 py-2 text-sm font-semibold text-white hover:bg-navy-800">Agregar horario</button>
            </form>
        </div>
    @else
        <p class="mt-4 max-w-2xl text-sm text-navy-400">💡 Guarda primero al especialista; luego podrás definir sus <strong>horarios de atención</strong> en esta misma pantalla.</p>
    @endif
</x-layouts.panel>
