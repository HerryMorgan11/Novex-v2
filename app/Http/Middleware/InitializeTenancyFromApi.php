<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Facades\Tenancy;
use Symfony\Component\HttpFoundation\Response;

/**
 * Inicializa tenancy para rutas de API externas a partir del header X-Tenant-Id.
 */
class InitializeTenancyFromApi
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = $request->header('X-Tenant-Id');

        if (empty($tenantId)) {
            return response()->json(['error' => 'Header X-Tenant-Id requerido.'], 400);
        }

        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            return response()->json(['error' => 'Tenant no encontrado.'], 404);
        }

        Tenancy::initialize($tenant);

        try {
            return $next($request);
        } finally {
            tenancy()->end();
        }
    }
}
