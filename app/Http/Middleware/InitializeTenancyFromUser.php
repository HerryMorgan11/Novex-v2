<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Stancl\Tenancy\Facades\Tenancy;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenancyFromUser
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && $user->current_tenant_id) {
            Tenancy::initialize($user->current_tenant_id);
        }

        return $next($request);
    }
}
