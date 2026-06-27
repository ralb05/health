@props([
    'title' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#1e2a4a">

    <title>{{ $title ?? config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-dvh text-navy-900 antialiased">
    {{--
        Marco tipo "app": a pantalla completa en celular y centrado como un
        teléfono en escritorio. Todas las pantallas heredan este contenedor.
    --}}
    <div class="mx-auto flex min-h-dvh w-full max-w-md flex-col bg-white shadow-sm md:my-6 md:min-h-0 md:rounded-3xl md:shadow-xl md:overflow-hidden">
        <main class="flex flex-1 flex-col">
            {{ $slot }}
        </main>

        @isset($nav)
            {{ $nav }}
        @endisset
    </div>
</body>
</html>
