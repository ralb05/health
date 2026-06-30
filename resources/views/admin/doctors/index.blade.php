<x-layouts.panel title="Especialistas" active="especialistas">
    <x-slot:heading>Especialistas</x-slot:heading>
    <x-slot:actions>
        <x-button :href="route('admin.doctors.create')" class="!w-auto px-5 py-2.5">Nuevo especialista</x-button>
    </x-slot:actions>

    <div class="overflow-x-auto rounded-2xl bg-white ring-1 ring-navy-100/70 shadow-sm">
        <table class="w-full text-sm">
            <thead class="border-b border-navy-100 text-left text-navy-400">
                <tr>
                    <th class="px-5 py-3 font-medium">Especialista</th>
                    <th class="px-5 py-3 font-medium">Especialidad</th>
                    <th class="px-5 py-3 font-medium">Precio</th>
                    <th class="px-5 py-3 font-medium">Acceso</th>
                    <th class="px-5 py-3 font-medium">Estado</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-navy-100">
                @foreach ($doctors as $d)
                    <tr>
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <x-avatar :name="$d->full_name" :src="$d->photo_url" size="h-10 w-10" />
                                <div>
                                    <p class="font-medium text-navy-700">{{ $d->full_name }}</p>
                                    <p class="text-xs text-navy-400">{{ $d->title }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-navy-600">{{ $d->specialty->name }}</td>
                        <td class="px-5 py-3 text-navy-600">{{ $d->price_formatted }}</td>
                        <td class="px-5 py-3 text-navy-500">
                            {{ $d->user?->email ?? '—' }}
                        </td>
                        <td class="px-5 py-3">
                            <x-status-badge :status="$d->is_active ? 'approved' : 'rejected'" :label="$d->is_active ? 'Activo' : 'Inactivo'" />
                        </td>
                        <td class="px-5 py-3 text-right whitespace-nowrap">
                            <a href="{{ route('admin.doctors.edit', $d) }}" class="font-medium text-navy-600 hover:underline">Editar</a>
                            <form method="POST" action="{{ route('admin.doctors.toggle', $d) }}" class="ml-3 inline">
                                @csrf @method('PATCH')
                                <button class="font-medium text-navy-400 hover:text-navy-700">{{ $d->is_active ? 'Desactivar' : 'Activar' }}</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-layouts.panel>
