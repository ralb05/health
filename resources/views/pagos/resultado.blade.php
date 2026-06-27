@php
    $config = [
        'success' => [
            'bg' => 'bg-mint-100', 'fg' => 'text-mint-500',
            'icon' => 'M5 13l4 4L19 7',
            'title' => '¡Cita confirmada!',
            'text' => 'Tu pago se realizó con éxito. Recibirás el enlace de la videollamada antes de la cita.',
        ],
        'pending' => [
            'bg' => 'bg-amber-100', 'fg' => 'text-amber-600',
            'icon' => 'M12 8v4l3 2M12 21a9 9 0 1 1 0-18 9 9 0 0 1 0 18Z',
            'title' => 'Pago en proceso',
            'text' => 'Tu pago está siendo procesado. Te avisaremos cuando se confirme tu cita.',
        ],
        'failure' => [
            'bg' => 'bg-red-50', 'fg' => 'text-red-500',
            'icon' => 'M6 6l12 12M18 6 6 18',
            'title' => 'No se pudo completar el pago',
            'text' => 'Tu pago fue rechazado o cancelado. Puedes intentarlo de nuevo.',
        ],
    ][$state];
@endphp

<x-layouts.app title="Resultado del pago">
    <div class="flex flex-1 flex-col items-center justify-center px-6 py-16 text-center">
        <span class="inline-flex h-20 w-20 items-center justify-center rounded-full {{ $config['bg'] }} {{ $config['fg'] }}">
            <svg class="h-10 w-10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $config['icon'] }}"/></svg>
        </span>

        <h1 class="mt-6 text-2xl font-semibold text-navy-700">{{ $config['title'] }}</h1>
        <p class="mt-2 max-w-xs text-sm text-navy-400">{{ $config['text'] }}</p>

        @if ($state === 'success')
            <x-card class="mt-8 w-full text-left">
                <div class="flex items-center gap-3">
                    <x-avatar :name="$appointment->doctor->full_name" :src="$appointment->doctor->photo_url" size="h-12 w-12" />
                    <div>
                        <p class="font-semibold text-navy-700">{{ $appointment->doctor->full_name }}</p>
                        <p class="text-sm text-navy-400">{{ $appointment->starts_at->isoFormat('dddd D [de] MMMM') }} · {{ $appointment->starts_at->format('g:i a') }}</p>
                    </div>
                </div>
            </x-card>
        @endif

        <div class="mt-8 w-full space-y-2">
            <x-button :href="route('citas.show', $appointment)">Ver mi cita</x-button>
            @if ($state === 'failure')
                <form method="POST" action="{{ route('payment.checkout', $appointment) }}">
                    @csrf
                    <button type="submit" class="w-full py-2 text-sm font-medium text-navy-500 hover:text-navy-700">Intentar pago de nuevo</button>
                </form>
            @else
                <x-button :href="route('inicio')" variant="secondary">Ir al inicio</x-button>
            @endif
        </div>
    </div>
</x-layouts.app>
