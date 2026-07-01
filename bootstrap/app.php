<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Confiar en el proxy de la plataforma (Railway/Render/Fly) para que
        // Laravel detecte HTTPS vía X-Forwarded-Proto y genere URLs correctas.
        $middleware->trustProxies(at: '*');

        // Cabeceras de seguridad en todas las respuestas web.
        $middleware->web(append: [
            \App\Http\Middleware\SecurityHeaders::class,
        ]);

        // El webhook de Mercado Pago llega sin token CSRF (servidor-a-servidor).
        $middleware->validateCsrfTokens(except: [
            'webhooks/mercadopago',
        ]);

        // Alias para proteger rutas por rol: ->middleware('role:admin')
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserHasRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
