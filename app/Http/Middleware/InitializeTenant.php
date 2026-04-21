<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Variante estricta de CheckHasTenant: aborta 403 si el usuario no tiene tenant.
 *
 * Útil para zonas que exigen tenancy obligatorio (API interna, acciones que no
 * deben caer nunca sin contexto de tenant). No muestra modal; simplemente falla.
 */
class InitializeTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $tenant = $user->current_tenant_id
            ? Tenant::query()->find($user->current_tenant_id)
            : null;

        if (! $tenant) {
            $tenant = $user->memberships()
                ->where('status', 'active')
                ->latest('id')
                ->first()
                ?->tenant;
        }

        abort_if(! $tenant, 403, 'Tenant not found');

        tenancy()->initialize($tenant);

        try {
            return $next($request);
        } finally {
            tenancy()->end();
        }
    }
}
