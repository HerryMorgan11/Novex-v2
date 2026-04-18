<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Facades\Tenancy;

class InitializeTenancyFromApi
{
    public function handle(Request $request, Closure $next)
    {
        $tenantId = $request->header('X-Tenant-Id');

        if (empty($tenantId)) {
            return response()->json([
                'error' => 'Header X-Tenant-Id requerido.',
            ], 400);
        }

        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            return response()->json([
                'error' => 'Tenant no encontrado para el X-Tenant-Id proporcionado.',
            ], 404);
        }

        Tenancy::initialize($tenant);

        try {
            return $next($request);
        } finally {
            tenancy()->end();
        }
    }
}
