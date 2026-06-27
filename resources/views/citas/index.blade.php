<x-layouts.app title="Mis citas">
    <div class="flex flex-1 flex-col px-6 pt-10 pb-6">
        <h1 class="text-2xl font-semibold text-navy-700">Mis citas</h1>
        <p class="mt-1 text-sm text-navy-400">Tus consultas próximas y pasadas.</p>

        @if (session('status'))
            <div class="mt-5 rounded-xl bg-mint-100 px-4 py-3 text-sm font-medium text-mint-500">{{ session('status') }}</div>
        @endif

        @if ($upcoming->isEmpty() && $past->isEmpty())
            {{-- Estado vacío --}}
            <div class="mt-10 flex flex-1 flex-col items-center justify-center gap-3 text-center text-navy-400">
                <span class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-lavender-100">
                    <svg class="h-8 w-8 text-navy-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7 3v3m10-3v3M4 9h16M5 6h14a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1Z"/></svg>
                </span>
                <p class="text-sm">Aún no tienes citas.</p>
                <x-button :href="route('inicio')" class="mt-2 max-w-xs">Agendar una cita</x-button>
            </div>
        @else
            {{-- Próximas --}}
            <h2 class="mt-7 mb-3 text-sm font-semibold tracking-wide text-navy-400 uppercase">Próximas</h2>
            @if ($upcoming->isEmpty())
                <x-card class="text-sm text-navy-400">No tienes citas próximas.</x-card>
            @else
                <div class="space-y-3">
                    @foreach ($upcoming as $appointment)
                        <x-appointment-row :appointment="$appointment" />
                    @endforeach
                </div>
            @endif

            {{-- Pasadas --}}
            @if ($past->isNotEmpty())
                <h2 class="mt-7 mb-3 text-sm font-semibold tracking-wide text-navy-400 uppercase">Pasadas</h2>
                <div class="space-y-3">
                    @foreach ($past as $appointment)
                        <x-appointment-row :appointment="$appointment" />
                    @endforeach
                </div>
            @endif
        @endif
    </div>

    <x-slot:nav>
        <x-bottom-nav active="citas" />
    </x-slot:nav>
</x-layouts.app>
