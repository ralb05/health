<x-layouts.app title="Recuperar contraseña">
    <div class="flex flex-1 flex-col px-6 pt-8 pb-10">
        <a href="{{ route('login') }}" class="inline-flex items-center gap-1 text-sm font-medium text-navy-400 hover:text-navy-600">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Volver
        </a>

        <div class="mt-6 flex flex-col items-center text-center">
            <x-logo size="h-12 w-12" />
            <h1 class="mt-3 text-2xl font-semibold text-navy-700">¿Olvidaste tu contraseña?</h1>
            <p class="mt-1 max-w-xs text-sm text-navy-400">
                Escribe tu correo y te enviaremos un enlace para restablecerla.
            </p>
        </div>

        @if (session('status'))
            <div class="mt-6 rounded-xl bg-mint-100 px-4 py-3 text-sm font-medium text-mint-500">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="mt-8 space-y-4">
            @csrf

            <x-field label="Correo electrónico" name="email" type="email" required autofocus placeholder="tucorreo@ejemplo.com" />

            <div class="pt-2">
                <x-button type="submit">Enviar enlace de recuperación</x-button>
            </div>
        </form>
    </div>
</x-layouts.app>
