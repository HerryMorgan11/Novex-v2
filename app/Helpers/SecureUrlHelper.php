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

        // If request is secure, ensure the URL is HTTPS
        if (request()->isSecure() && str_starts_with($url, 'http://')) {
            $url = str_replace('http://', 'https://', $url);
        }

        return $url;
    }

    /**
     * Generate a secure URL that respects HTTPS in production.
     */
    public static function secureUrl(string $path, mixed $parameters = []): string
    {
        return url($path, $parameters, request()->isSecure());
    }
}
