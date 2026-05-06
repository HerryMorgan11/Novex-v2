<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    /**
     * Handle an incoming request.
     *
     * Force HTTPS protocol in production and ensure URL generation uses HTTPS.
     *
     * @param  Closure(Request): (Response)  $next
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
