@php $editing = $specialty->exists; @endphp

<x-layouts.panel :title="$editing ? 'Editar especialidad' : 'Nueva especialidad'" active="especialidades">
    <x-slot:heading>{{ $editing ? 'Editar especialidad' : 'Nueva especialidad' }}</x-slot:heading>

    <form method="POST"
          action="{{ $editing ? route('admin.specialties.update', $specialty) : route('admin.specialties.store') }}"
          class="max-w-xl space-y-4 rounded-2xl bg-white p-6 ring-1 ring-navy-100/70 shadow-sm">
        @csrf
        @if ($editing) @method('PUT') @endif

        <x-field label="Nombre" name="name" :value="$specialty->name" required />

        <div class="space-y-1.5">
            <label for="description" class="block text-sm font-medium text-navy-700">Descripción</label>
            <textarea id="description" name="description" rows="3"
                      class="w-full rounded-xl border border-navy-200 bg-white px-4 py-3 text-navy-900 focus:border-navy-400 focus:ring-2 focus:ring-navy-100 focus:outline-none">{{ old('description', $specialty->description) }}</textarea>
        </div>

        <div class="space-y-1.5">
            <label for="icon" class="block text-sm font-medium text-navy-700">Ícono</label>
            <select id="icon" name="icon" class="w-full rounded-xl border border-navy-200 bg-white px-4 py-3 text-navy-900 focus:border-navy-400 focus:ring-2 focus:ring-navy-100 focus:outline-none">
                <option value="brain" @selected(old('icon', $specialty->icon) === 'brain')>Cerebro (psiquiatría)</option>
                <option value="chat" @selected(old('icon', $specialty->icon) === 'chat')>Chat (psicología)</option>
            </select>
        </div>

        <label class="flex items-center gap-2 text-sm text-navy-600">
            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $specialty->is_active ?? true))
                   class="h-4 w-4 rounded border-navy-200 text-navy-700 focus:ring-navy-300">
            Activa (visible para pacientes)
        </label>

        <div class="flex gap-3 pt-2">
            <x-button type="submit" class="!w-auto px-6">Guardar</x-button>
            <a href="{{ route('admin.specialties.index') }}" class="px-4 py-3 text-sm font-medium text-navy-400 hover:text-navy-700">Cancelar</a>
        </div>
    </form>
</x-layouts.panel>
