<x-layouts.panel title="Especialidades" active="especialidades">
    <x-slot:heading>Especialidades</x-slot:heading>
    <x-slot:actions>
        <x-button :href="route('admin.specialties.create')" class="!w-auto px-5 py-2.5">Nueva especialidad</x-button>
    </x-slot:actions>

    <div class="overflow-hidden rounded-2xl bg-white ring-1 ring-navy-100/70 shadow-sm">
        <table class="w-full text-sm">
            <thead class="border-b border-navy-100 text-left text-navy-400">
                <tr>
                    <th class="px-5 py-3 font-medium">Nombre</th>
                    <th class="px-5 py-3 font-medium">Especialistas</th>
                    <th class="px-5 py-3 font-medium">Estado</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-navy-100">
                @foreach ($specialties as $s)
                    <tr>
                        <td class="px-5 py-3">
                            <p class="font-medium text-navy-700">{{ $s->name }}</p>
                            <p class="text-xs text-navy-400">{{ $s->slug }}</p>
                        </td>
                        <td class="px-5 py-3 text-navy-600">{{ $s->doctors_count }}</td>
                        <td class="px-5 py-3">
                            <x-status-badge :status="$s->is_active ? 'approved' : 'rejected'" :label="$s->is_active ? 'Activa' : 'Inactiva'" />
                        </td>
                        <td class="px-5 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.specialties.edit', $s) }}" class="font-medium text-navy-600 hover:underline">Editar</a>
                            <form method="POST" action="{{ route('admin.specialties.toggle', $s) }}" class="ml-3 inline">
                                @csrf @method('PATCH')
                                <button class="font-medium text-navy-400 hover:text-navy-700">{{ $s->is_active ? 'Desactivar' : 'Activar' }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.panel>
