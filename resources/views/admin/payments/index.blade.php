<x-layouts.panel title="Pagos" active="pagos">
    <x-slot:heading>Pagos</x-slot:heading>
    <x-slot:actions>
        <div class="text-right">
            <p class="text-xs text-navy-400">Total recaudado</p>
            <p class="text-lg font-semibold text-navy-700">${{ number_format($totalApproved, 0, ',', '.') }} COP</p>
        </div>
    </x-slot:actions>

    <div class="overflow-x-auto rounded-2xl bg-white ring-1 ring-navy-100/70 shadow-sm">
        <table class="w-full text-sm">
            <thead class="border-b border-navy-100 text-left text-navy-400">
                <tr>
                    <th class="px-5 py-3 font-medium">Paciente</th>
                    <th class="px-5 py-3 font-medium">Especialista</th>
                    <th class="px-5 py-3 font-medium">Monto</th>
                    <th class="px-5 py-3 font-medium">Estado</th>
                    <th class="px-5 py-3 font-medium">Pagado</th>
                    <th class="px-5 py-3 font-medium">Medio</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-navy-100">
                @forelse ($payments as $p)
                    <tr>
                        <td class="px-5 py-3 font-medium text-navy-700">{{ $p->appointment->patient->name ?? '—' }}</td>
                        <td class="px-5 py-3 text-navy-600">{{ $p->appointment->doctor->full_name ?? '—' }}</td>
                        <td class="px-5 py-3 text-navy-600">${{ number_format($p->amount_cop, 0, ',', '.') }} COP</td>
                        <td class="px-5 py-3"><x-status-badge :status="$p->status" /></td>
                        <td class="px-5 py-3 text-navy-500">{{ $p->paid_at?->isoFormat('D MMM YYYY HH:mm') ?? '—' }}</td>
                        <td class="px-5 py-3 text-navy-500">{{ ucfirst($p->provider) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-5 py-6 text-center text-navy-400">Aún no hay pagos.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $payments->links() }}</div>
</x-layouts.panel>
