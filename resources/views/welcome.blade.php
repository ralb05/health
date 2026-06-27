<x-layouts.app :title="config('app.name')">
    <div class="relative flex flex-1 flex-col px-6 pt-16 pb-10">
        {{-- Olas decorativas de fondo (parte inferior) --}}
        <div class="pointer-events-none absolute inset-x-0 bottom-0 -z-0 overflow-hidden">
            <svg viewBox="0 0 400 220" class="w-full" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M0 120 C 90 90 150 160 230 130 S 360 80 400 110 L400 220 L0 220 Z" fill="#eceaf8"/>
                <path d="M0 160 C 100 130 160 190 250 160 S 360 130 400 150 L400 220 L0 220 Z" fill="#d8f3ee" fill-opacity="0.7"/>
            </svg>
        </div>

        {{-- Logo + nombre --}}
        <div class="relative z-10 flex flex-1 flex-col items-center justify-center text-center">
            <x-logo size="h-20 w-20" />
            <h1 class="mt-6 text-4xl font-semibold tracking-tight text-navy-700">Mind &amp; Health</h1>
            <p class="mt-3 max-w-xs text-lg leading-relaxed text-navy-400">
                Tu bienestar mental,<br>nuestra prioridad
            </p>
        </div>

        {{-- Acciones --}}
        <div class="relative z-10 space-y-3">
            <x-button :href="route('register')">Comenzar</x-button>
            <x-button :href="route('login')" variant="secondary">Iniciar sesión</x-button>
        </div>
    </div>
</x-layouts.app>
