<x-layouts.app title="Crear cuenta">
    <div class="flex flex-1 flex-col px-6 pt-8 pb-10">
        {{-- Encabezado --}}
        <a href="{{ route('welcome') }}" class="inline-flex items-center gap-1 text-sm font-medium text-navy-400 hover:text-navy-600">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            Volver
        </a>

        <div class="mt-6 flex flex-col items-center text-center">
            <x-logo size="h-12 w-12" />
            <h1 class="mt-3 text-2xl font-semibold text-navy-700">Crea tu cuenta</h1>
            <p class="mt-1 text-sm text-navy-400">Empieza a cuidar tu bienestar mental.</p>
        </div>

        {{-- Formulario --}}
        <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-4">
            @csrf

            <x-field label="Nombre completo" name="name" required autocomplete="name" autofocus placeholder="Tu nombre" />
            <x-field label="Correo electrónico" name="email" type="email" required autocomplete="email" placeholder="tucorreo@ejemplo.com" />
            <x-field label="Celular" name="phone" type="tel" autocomplete="tel" placeholder="300 123 4567" hint="Para recordatorios de tus citas." />
            <x-field label="Contraseña" name="password" type="password" required autocomplete="new-password" placeholder="Mínimo 8 caracteres" />
            <x-field label="Confirmar contraseña" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="Repite la contraseña" />

            <div class="pt-2">
                <x-button type="submit">Crear cuenta</x-button>
            </div>
        </form>

        <p class="mt-6 text-center text-sm text-navy-400">
            ¿Ya tienes cuenta?
            <a href="{{ route('login') }}" class="font-semibold text-navy-700 hover:underline">Iniciar sesión</a>
        </p>
    </div>
</x-layouts.app>
