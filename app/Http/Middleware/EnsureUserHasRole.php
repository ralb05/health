<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Permite el acceso solo si el usuario autenticado tiene alguno de los roles dados.
     * Uso en rutas: ->middleware('role:admin')  o  ->middleware('role:admin,doctor')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        abort_unless($request->user() && in_array($request->user()->role, $roles, true), 403);

        return $next($request);
    }
}
