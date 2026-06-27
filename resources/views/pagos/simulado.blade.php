<x-layouts.app title="Pago (simulado)">
    <div class="flex flex-1 flex-col px-6 pt-8 pb-10">
        {{-- Aviso de modo simulado --}}
        <div class="rounded-xl bg-amber-100 px-4 py-2.5 text-center text-xs font-semibold text-amber-700">
            ⚙️ Pasarela de PRUEBA (modo simulado). Pega tus llaves de Mercado Pago para el pago real.
        </div>

        <div class="mt-6 flex flex-col items-center text-center">
            <span class="inline-flex h-14 w-14 items-center justify-center rounded-2xl bg-navy-700 text-white">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M3 10h18M5 6h14a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1Z"/></svg>
            </span>
            <h1 class="mt-3 text-xl font-semibold text-navy-700">Confirmar pago</h1>
            <p class="mt-1 text-sm text-navy-400">Consulta con {{ $appointment->doctor->full_name }}</p>
        </div>

        {{-- Resumen --}}
        <x-card class="mt-6">
            <div class="flex items-center justify-between text-sm">
                <span class="text-navy-400">Fecha</span>
                <span class="font-medium text-navy-700">{{ $appointment->starts_at->isoFormat('ddd D MMM') }} · {{ $appointment->starts_at->format('g:i a') }}</span>
            </div>
            <div class="mt-2 flex items-center justify-between text-sm">
                <span class="text-navy-400">Tipo</span>
                <span class="font-medium text-navy-700">Consulta on-line</span>
            </div>
            <div class="mt-3 border-t border-navy-100 pt-3 flex items-center justify-between">
                <span class="font-semibold text-navy-700">Total</span>
                <span class="text-lg font-semibold text-navy-700">{{ $appointment->price_formatted }}</span>
            </div>
        </x-card>

        <p class="mt-6 text-center text-xs text-navy-300">
            Elige el resultado para simular la respuesta de la pasarela:
        </p>

        <div class="mt-3 space-y-2">
            <form method="POST" action="{{ route('payment.simulate', $appointment) }}">
                @csrf
                <input type="hidden" name="result" value="approved">
                <x-button type="submit">✅ Pagar (aprobar)</x-button>
            </form>
            <form method="POST" action="{{ route('payment.simulate', $appointment) }}">
                @csrf
                <input type="hidden" name="result" value="rejected">
                <button type="submit" class="w-full rounded-2xl border border-red-200 bg-white px-6 py-4 text-base font-semibold text-red-500 hover:bg-red-50">
                    ❌ Simular rechazo
                </button>
            </form>
        </div>
    </div>
</x-layouts.app>
