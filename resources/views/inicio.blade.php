<x-layouts.app title="Inicio">
    <div class="flex flex-1 flex-col px-6 pt-10 pb-6">
        {{-- Saludo --}}
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-navy-700">
                    ¡Hola, {{ Str::of(auth()->user()->name)->before(' ') }}! 👋
                </h1>
                <p class="mt-1 text-sm text-navy-400">¿Cómo te sientes hoy?</p>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-full p-2 text-navy-400 hover:bg-navy-50 hover:text-navy-700" title="Cerrar sesión">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>
                    </svg>
                </button>
            </form>
        </div>

        {{-- Banner principal --}}
        <x-card class="mt-6 bg-lavender-100 ring-lavender-200">
            <p class="text-base font-semibold text-navy-700">Cuidar tu mente es el primer paso.</p>
            <p class="mt-1 text-sm text-navy-500">Estamos aquí para acompañarte.</p>
        </x-card>

        {{-- Especialidades --}}
        <h2 class="mt-7 mb-3 text-sm font-semibold tracking-wide text-navy-400 uppercase">
            Nuestros servicios
        </h2>
        <div class="grid grid-cols-2 gap-3">
            @foreach ($specialties as $specialty)
                <x-specialty-card :specialty="$specialty" />
            @endforeach
        </div>

        {{-- Próxima cita --}}
        <div class="mt-7 mb-3 flex items-center justify-between">
            <h2 class="text-sm font-semibold tracking-wide text-navy-400 uppercase">Próxima cita</h2>
            @if ($nextAppointment)
                <a href="{{ route('citas.index') }}" class="text-sm font-medium text-navy-500 hover:text-navy-700">Ver todas</a>
            @endif
        </div>
        @if ($nextAppointment)
            <x-appointment-row :appointment="$nextAppointment" />
        @else
            <x-card class="flex items-center gap-3 text-navy-400">
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-navy-50">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M7 3v3m10-3v3M4 9h16M5 6h14a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1Z"/>
                    </svg>
                </span>
                <p class="text-sm">Aún no tienes citas. Agenda con un especialista cuando quieras.</p>
            </x-card>
        @endif

        {{-- Banner consultas online --}}
        <x-card class="mt-6 bg-mint-100 ring-mint-200">
            <p class="font-semibold text-navy-700">Consultas on-line</p>
            <p class="mt-1 text-sm text-navy-500">Desde donde estés, estamos contigo.</p>
        </x-card>
    </div>

    <x-slot:nav>
        <x-bottom-nav active="inicio" />
    </x-slot:nav>
</x-layouts.app>
