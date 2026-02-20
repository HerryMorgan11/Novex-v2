<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Support\Facades\Auth;

class InitializeTenant
{
    public function handle($request, Closure $next)
    {
        if (! Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();
        $tenant = null;

        if ($user->current_tenant_id) {
            $tenant = Tenant::query()->find($user->current_tenant_id);
        }

        if (! $tenant) {
            $membership = $user->memberships()
                ->where('status', 'active')
                ->latest('id')
                ->first();

            $tenant = $membership?->tenant;
        }

        if (! $tenant) {
            abort(403, 'Tenant not found');
        }

        if (! function_exists('tenancy')) {
            abort(500, 'Stancl tenancy helper not available');
        }

        tenancy()->initialize($tenant);

        try {
            return $next($request);
        } finally {
            tenancy()->end();
        }
    }
}
