<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Fuerza el uso de HTTPS en producción.
 *
 * Confía en los headers de proxy (X-Forwarded-Proto) para detectar
 * el esquema original de la petición.
 */
class ForceHttps
{
    /**
     * Redirige a HTTPS si la petición no es segura en producción.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Trust proxy headers for URL scheme detection (X-Forwarded-Proto, X-Forwarded-For)
        if ($request->header('X-Forwarded-Proto') === 'https') {
            $_SERVER['HTTPS'] = 'on';
        }

        // Force HTTPS in production
        if (app()->isProduction() && ! $request->isSecure()) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}
