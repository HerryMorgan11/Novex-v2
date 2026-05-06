<?php

namespace App\Helpers;

/**
 * Blade helper for generating secure URLs for forms in production.
 * Ensures HTTPS protocol is used when the request uses HTTPS.
 */
class SecureUrlHelper
{
    /**
     * Generate a secure route URL that respects HTTPS in production.
     */
    public static function secureRoute(string $route, mixed $parameters = []): string
    {
        $url = route($route, $parameters);

        // In production, force HTTPS
        if (app()->isProduction()) {
            $url = str_replace('http://', 'https://', $url);
        }

        return $url;
    }

    /**
     * Generate a secure URL that respects HTTPS in production.
     */
    public static function secureUrl(string $path, mixed $parameters = []): string
    {
        $url = url($path, $parameters);

        // In production, force HTTPS
        if (app()->isProduction()) {
            $url = str_replace('http://', 'https://', $url);
        }

        return $url;
    }
}
