<x-layouts.app title="Restablecer contraseña">
    <div class="flex flex-1 flex-col px-6 pt-10 pb-10">
        <div class="flex flex-col items-center text-center">
            <x-logo size="h-12 w-12" />
            <h1 class="mt-3 text-2xl font-semibold text-navy-700">Nueva contraseña</h1>
            <p class="mt-1 text-sm text-navy-400">Crea una contraseña nueva para tu cuenta.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="mt-8 space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <x-field label="Correo electrónico" name="email" type="email" :value="$request->email" required autocomplete="username" />
            <x-field label="Nueva contraseña" name="password" type="password" required autocomplete="new-password" placeholder="Mínimo 8 caracteres" />
            <x-field label="Confirmar contraseña" name="password_confirmation" type="password" required autocomplete="new-password" placeholder="Repite la contraseña" />

            <div class="pt-2">
                <x-button type="submit">Restablecer contraseña</x-button>
            </div>
        </form>
    </div>
</x-layouts.app>
