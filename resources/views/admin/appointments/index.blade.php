<x-layouts.panel title="Citas" active="citas">
    <x-slot:heading>Citas</x-slot:heading>

    {{-- Filtros --}}
    <form method="GET" class="mb-4 flex flex-wrap items-end gap-3">
        <div class="space-y-1.5">
            <label class="block text-xs font-medium text-navy-500">Estado</label>
            <select name="status" class="rounded-xl border border-navy-200 bg-white px-3 py-2 text-sm focus:border-navy-400 focus:ring-2 focus:ring-navy-100 focus:outline-none">
                <option value="">Todos</option>
                @foreach (['pending_payment'=>'Pendiente de pago','confirmed'=>'Confirmada','completed'=>'Completada','cancelled'=>'Cancelada','expired'=>'Expirada'] as $val=>$lbl)
                    <option value="{{ $val }}" @selected(request('status')===$val)>{{ $lbl }}</option>
                @endforeach
            </select>
        </div>
        <div class="space-y-1.5">
            <label class="block text-xs font-medium text-navy-500">Especialista</label>
            <select name="doctor" class="rounded-xl border border-navy-200 bg-white px-3 py-2 text-sm focus:border-navy-400 focus:ring-2 focus:ring-navy-100 focus:outline-none">
                <option value="">Todos</option>
                @foreach ($doctors as $d)
                    <option value="{{ $d->id }}" @selected(request('doctor')==$d->id)>{{ $d->full_name }}</option>
                @endforeach
            </select>
        </div>
        <button class="rounded-xl bg-navy-700 px-5 py-2 text-sm font-semibold text-white hover:bg-navy-800">Filtrar</button>
        @if (request()->hasAny(['status','doctor']))
            <a href="{{ route('admin.citas.index') }}" class="px-2 py-2 text-sm text-navy-400 hover:text-navy-700">Limpiar</a>
        @endif
    </form>

    <div class="overflow-x-auto rounded-2xl bg-white ring-1 ring-navy-100/70 shadow-sm">
        <table class="w-full text-sm">
            <thead class="border-b border-navy-100 text-left text-navy-400">
                <tr>
                    <th class="px-5 py-3 font-medium">Paciente</th>
                    <th class="px-5 py-3 font-medium">Especialista</th>
                    <th class="px-5 py-3 font-medium">Fecha</th>
                    <th class="px-5 py-3 font-medium">Precio</th>
                    <th class="px-5 py-3 font-medium">Estado</th>
                    <th class="px-5 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-navy-100">
                @forelse ($appointments as $a)
                    <tr>
                        <td class="px-5 py-3 font-medium text-navy-700">{{ $a->patient->name }}</td>
                        <td class="px-5 py-3 text-navy-600">{{ $a->doctor->full_name }}</td>
                        <td class="px-5 py-3 text-navy-600">{{ $a->starts_at->isoFormat('D MMM YYYY') }} · {{ $a->starts_at->format('g:i a') }}</td>
                        <td class="px-5 py-3 text-navy-600">{{ $a->price_formatted }}</td>
                        <td class="px-5 py-3"><x-status-badge :status="$a->status" :label="$a->status_label" /></td>
                        <td class="px-5 py-3 text-right">
                            @if ($a->is_active)
                                <form method="POST" action="{{ route('admin.citas.cancel', $a) }}" onsubmit="return confirm('¿Cancelar esta cita?')">
                                    @csrf
                                    <button class="text-sm font-medium text-red-400 hover:text-red-600">Cancelar</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-6 text-center text-navy-400">No hay citas con esos filtros.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $appointments->links() }}</div>
</x-layouts.panel>
