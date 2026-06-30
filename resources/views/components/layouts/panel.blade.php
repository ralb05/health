@props([
    'title' => null,
    'active' => null,
])

@php
    $user = auth()->user();
    $links = $user?->isAdmin()
        ? [
            'resumen' => ['label' => 'Resumen', 'href' => route('admin.dashboard')],
            'citas' => ['label' => 'Citas', 'href' => route('admin.citas.index')],
            'especialistas' => ['label' => 'Especialistas', 'href' => route('admin.doctors.index')],
            'especialidades' => ['label' => 'Especialidades', 'href' => route('admin.specialties.index')],
            'pagos' => ['label' => 'Pagos', 'href' => route('admin.payments.index')],
        ]
        : [
            'agenda' => ['label' => 'Mi agenda', 'href' => route('doctor.dashboard')],
            'disponibilidad' => ['label' => 'Disponibilidad', 'href' => route('doctor.schedules.index')],
        ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#1e2a4a">
    <title>{{ $title ? $title.' · ' : '' }}Panel · {{ config('app.name') }}</title>
    <link rel="manifest" href="/manifest.webmanifest">
    <link rel="icon" type="image/svg+xml" href="/icon.svg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-dvh bg-[#f6f7fb] text-navy-900 antialiased">
    {{-- Barra superior --}}
    <header class="border-b border-navy-100 bg-white">
        <div class="mx-auto flex max-w-5xl items-center justify-between gap-4 px-4 py-3">
            <div class="flex items-center gap-2">
                <x-logo size="h-8 w-8" />
                <div class="leading-tight">
                    <p class="font-semibold text-navy-700">Mind &amp; Health</p>
                    <p class="text-xs text-navy-400">Panel · {{ $user->isAdmin() ? 'Administrador' : 'Especialista' }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="rounded-lg px-3 py-1.5 text-sm font-medium text-navy-500 hover:bg-navy-50 hover:text-navy-700">Salir</button>
            </form>
        </div>

        {{-- Navegación --}}
        <nav class="mx-auto max-w-5xl px-2">
            <ul class="flex gap-1 overflow-x-auto">
                @foreach ($links as $key => $link)
                    <li>
                        <a href="{{ $link['href'] }}"
                           @class([
                               'inline-block whitespace-nowrap border-b-2 px-3 py-2.5 text-sm font-medium transition',
                               'border-navy-700 text-navy-700' => $active === $key,
                               'border-transparent text-navy-400 hover:text-navy-600' => $active !== $key,
                           ])>{{ $link['label'] }}</a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </header>

    {{-- Contenido --}}
    <main class="mx-auto max-w-5xl px-4 py-6">
        @if (session('status'))
            <div class="mb-4 rounded-xl bg-mint-100 px-4 py-3 text-sm font-medium text-mint-500">{{ session('status') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded-xl bg-red-50 px-4 py-3 text-sm font-medium text-red-600">{{ session('error') }}</div>
        @endif

        @isset($heading)
            <div class="mb-5 flex items-center justify-between gap-4">
                <h1 class="text-xl font-semibold text-navy-700">{{ $heading }}</h1>
                @isset($actions){{ $actions }}@endisset
            </div>
        @endisset

        {{ $slot }}
    </main>
</body>
</html>
