<x-layouts.app title="Detalles de la cita">
    @php
        $doctor = $appointment->doctor;
        $statusStyles = [
            \App\Models\Appointment::STATUS_PENDING => 'bg-amber-100 text-amber-700',
            \App\Models\Appointment::STATUS_CONFIRMED => 'bg-mint-100 text-mint-500',
            \App\Models\Appointment::STATUS_COMPLETED => 'bg-navy-100 text-navy-600',
            \App\Models\Appointment::STATUS_CANCELLED => 'bg-red-50 text-red-500',
            \App\Models\Appointment::STATUS_EXPIRED => 'bg-red-50 text-red-500',
        ];
    @endphp

    <div class="flex flex-1 flex-col px-6 pt-8 pb-6">
        {{-- Volver --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('inicio') }}" class="inline-flex items-center gap-1 text-sm font-medium text-navy-400 hover:text-navy-600">
                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                Volver
            </a>
            <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $statusStyles[$appointment->status] ?? 'bg-navy-100 text-navy-600' }}">
                {{ $appointment->status_label }}
            </span>
        </div>

        <h1 class="mt-4 text-xl font-semibold text-navy-700">Detalles de la cita</h1>

        {{-- Mensajes --}}
        @if (session('status'))
            <div class="mt-4 rounded-xl bg-navy-50 px-4 py-3 text-sm font-medium text-navy-600">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="mt-4 rounded-xl bg-red-50 px-4 py-3 text-sm font-medium text-red-600">{{ session('error') }}</div>
        @endif

        {{-- Especialista --}}
        <x-card class="mt-5 flex items-center gap-3">
            <x-avatar :name="$doctor->full_name" :src="$doctor->photo_url" size="h-16 w-16" />
            <div class="min-w-0">
                <p class="font-semibold text-navy-700">{{ $doctor->full_name }}</p>
                <p class="text-sm text-navy-400">{{ $doctor->title }}</p>
                <x-rating :value="$doctor->rating" :count="$doctor->reviews_count" />
            </div>
        </x-card>

        {{-- Datos de la cita --}}
        <div class="mt-4 grid grid-cols-2 gap-3">
            <x-card padding="p-4">
                <p class="text-xs text-navy-300">Fecha</p>
                <p class="mt-0.5 font-semibold text-navy-700">{{ $appointment->starts_at->isoFormat('dddd D [de] MMMM') }}</p>
            </x-card>
            <x-card padding="p-4">
                <p class="text-xs text-navy-300">Hora</p>
                <p class="mt-0.5 font-semibold text-navy-700">{{ $appointment->starts_at->format('g:i a') }}</p>
            </x-card>
            <x-card padding="p-4">
                <p class="text-xs text-navy-300">Tipo de consulta</p>
                <p class="mt-0.5 font-semibold text-navy-700">On-line</p>
            </x-card>
            <x-card padding="p-4">
                <p class="text-xs text-navy-300">Precio</p>
                <p class="mt-0.5 font-semibold text-navy-700">{{ $appointment->price_formatted }}</p>
            </x-card>
        </div>

        {{-- Sobre la consulta --}}
        <x-card class="mt-4">
            <p class="font-semibold text-navy-700">Sobre la consulta</p>
            <p class="mt-1 text-sm text-navy-500">
                La consulta se realizará por videollamada. Recibirás el enlace antes de la cita.
            </p>
            <div class="mt-3 flex items-center gap-2 rounded-xl bg-lavender-100 px-3 py-2 text-sm text-navy-600">
                <svg class="h-5 w-5 shrink-0 text-navy-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l7 3v5c0 4.2-2.9 7.5-7 9-4.1-1.5-7-4.8-7-9V6l7-3Z"/></svg>
                Tu información está protegida. Consulta 100% confidencial.
            </div>
        </x-card>

        {{-- Aviso de expiración del hold --}}
        @if ($appointment->status === \App\Models\Appointment::STATUS_PENDING && $appointment->expires_at)
            <p class="mt-4 text-center text-xs text-navy-400">
                Reserva este cupo completando el pago antes de las
                <strong>{{ $appointment->expires_at->format('g:i a') }}</strong>.
            </p>
        @endif
    </div>

    {{-- Barra de acción según estado --}}
    @if ($appointment->status === \App\Models\Appointment::STATUS_PENDING)
        <div class="sticky bottom-0 space-y-2 border-t border-navy-100 bg-white/95 px-6 py-4 backdrop-blur">
            <form method="POST" action="{{ route('payment.checkout', $appointment) }}">
                @csrf
                <x-button type="submit">Confirmar y pagar</x-button>
            </form>
            <form method="POST" action="{{ route('citas.cancel', $appointment) }}">
                @csrf
                <button type="submit" class="w-full py-2 text-center text-sm font-medium text-navy-400 hover:text-navy-600">
                    Cancelar cita
                </button>
            </form>
        </div>
    @elseif ($appointment->status === \App\Models\Appointment::STATUS_CONFIRMED)
        <div class="sticky bottom-0 border-t border-navy-100 bg-white/95 px-6 py-4 backdrop-blur">
            @if ($appointment->meeting_url)
                <x-button :href="$appointment->meeting_url" target="_blank">Entrar a la videollamada</x-button>
            @else
                <p class="text-center text-sm text-navy-400">El especialista te enviará el enlace antes de la cita.</p>
            @endif
        </div>
    @elseif (in_array($appointment->status, [\App\Models\Appointment::STATUS_EXPIRED, \App\Models\Appointment::STATUS_CANCELLED]))
        <div class="sticky bottom-0 border-t border-navy-100 bg-white/95 px-6 py-4 backdrop-blur">
            <x-button :href="route('especialistas.show', $doctor)">Agendar de nuevo</x-button>
        </div>
    @endif
</x-layouts.app>
