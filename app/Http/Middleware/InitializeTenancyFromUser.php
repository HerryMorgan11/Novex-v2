<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Facades\Tenancy;
use Symfony\Component\HttpFoundation\Response;

/**
 * Inicializa tenancy a partir del tenant actual del usuario autenticado.
 *
 * Si el usuario no tiene tenant asignado, deja pasar sin inicializar.
 */
class InitializeTenancyFromUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (! $user || ! $user->current_tenant_id) {
            return $next($request);
        }

        Tenancy::initialize($user->current_tenant_id);

        try {
            return $next($request);
        } finally {
            tenancy()->end();
        }
    }
}
