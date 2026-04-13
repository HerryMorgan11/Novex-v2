<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckHasTenant
{
    /**
     * Middleware que verifica si el usuario autenticado tiene un tenant.
     * Si NO tiene, marca la request para mostrar el modal de creación de empresa.
     *
     * Si SÍ tiene, inicializa tenancy y continúa normalmente.
     */
    public function handle($request, Closure $next)
    {
        // Si no está autenticado, dejar pasar (login/register)
        if (! Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $hasTenant = false;
        $tenant = null;

        // Buscar tenant: primero intentar current_tenant_id
        if ($user->current_tenant_id) {
            $tenant = $user->currentTenant;
            $hasTenant = (bool) $tenant;
        }

        // Si no está en current_tenant_id, buscar en memberships con eager loading
        if (! $hasTenant) {
            // Cargar eager para evitar N+1
            $user->load(['memberships' => function ($query) {
                $query->where('status', 'active');
            }, 'memberships.tenant']);

            $membership = $user->memberships->sortByDesc('id')->first();

            if ($membership && $membership->tenant) {
                $tenant = $membership->tenant;
                $hasTenant = true;
                // Actualizar current_tenant_id para la próxima vez
                $user->update(['current_tenant_id' => $tenant->id]);
            }
        }

        // Si tiene tenant, inicializar tenancy
        if ($hasTenant && $tenant && function_exists('tenancy')) {
            tenancy()->initialize($tenant);

            try {
                return $next($request);
            } finally {
                tenancy()->end();
            }
        }

        // Si NO tiene tenant, marcar en la request pero permitir que continúe
        // El layout del dashboard mostrará el modal
        $request->attributes->set('show_company_modal', true);
        $request->attributes->set('user_id', $user->id);

        return $next($request);
    }
}
