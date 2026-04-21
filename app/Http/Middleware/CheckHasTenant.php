<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resuelve el tenant del usuario autenticado e inicializa tenancy durante el request.
 *
 * Si el usuario no tiene tenant asociado, deja pasar el request marcándolo para
 * que la capa de UI muestre el modal de creación de empresa.
 */
class CheckHasTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return $next($request);
        }

        /** @var User $user */
        $user = Auth::user();
        $tenant = $this->resolveTenant($user);

        if ($tenant === null) {
            // Sin tenant: el dashboard renderiza el modal de onboarding.
            $request->attributes->set('show_company_modal', true);
            $request->attributes->set('user_id', $user->id);

            return $next($request);
        }

        tenancy()->initialize($tenant);

        try {
            return $next($request);
        } finally {
            tenancy()->end();
        }
    }

    /**
     * Devuelve el tenant activo del usuario (por current_tenant_id o primera membership activa).
     * Actualiza current_tenant_id si hace falta para evitar futuras búsquedas.
     */
    private function resolveTenant(User $user): ?Tenant
    {
        if ($user->current_tenant_id && $user->currentTenant) {
            return $user->currentTenant;
        }

        $user->load(['memberships' => fn ($q) => $q->where('status', 'active'), 'memberships.tenant']);

        $membership = $user->memberships->sortByDesc('id')->first();

        if (! $membership?->tenant) {
            return null;
        }

        $user->update(['current_tenant_id' => $membership->tenant->id]);

        return $membership->tenant;
    }
}
