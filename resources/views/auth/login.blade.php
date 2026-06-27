<x-layouts.app title="Iniciar sesión">
    <div class="flex flex-1 flex-col px-6 pt-8 pb-10">
        {{-- Encabezado --}}
        <a href="{{ route('welcome') }}" class="inline-flex items-center gap-1 text-sm font-medium text-navy-400 hover:text-navy-600">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Volver
        </a>

        <div class="mt-6 flex flex-col items-center text-center">
            <x-logo size="h-12 w-12" />
            <h1 class="mt-3 text-2xl font-semibold text-navy-700">¡Hola de nuevo! 👋</h1>
            <p class="mt-1 text-sm text-navy-400">Inicia sesión para continuar.</p>
        </div>

        {{-- Mensaje de estado (ej. tras pedir recuperación) --}}
        @if (session('status'))
            <div class="mt-6 rounded-xl bg-mint-100 px-4 py-3 text-sm font-medium text-mint-500">
                {{ session('status') }}
            </div>
        @endif

        {{-- Formulario --}}
        <form method="POST" action="{{ route('login') }}" class="mt-8 space-y-4">
            @csrf

            <x-field label="Correo electrónico" name="email" type="email" required autocomplete="email" autofocus placeholder="tucorreo@ejemplo.com" />
            <x-field label="Contraseña" name="password" type="password" required autocomplete="current-password" placeholder="Tu contraseña" />

            <div class="flex items-center justify-between pt-1">
                <label for="remember_me" class="flex items-center gap-2 text-sm text-navy-500">
                    <input id="remember_me" name="remember" type="checkbox"
                           class="h-4 w-4 rounded border-navy-200 text-navy-700 focus:ring-navy-300">
                    Recordarme
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm font-medium text-navy-500 hover:text-navy-700 hover:underline">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
            </div>

            <div class="pt-2">
                <x-button type="submit">Iniciar sesión</x-button>
            </div>
        </form>

        <p class="mt-6 text-center text-sm text-navy-400">
            ¿No tienes cuenta?
            <a href="{{ route('register') }}" class="font-semibold text-navy-700 hover:underline">Crear cuenta</a>
        </p>
    </div>
</x-layouts.app>
