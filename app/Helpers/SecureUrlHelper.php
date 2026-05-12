<?php

namespace App\Helpers;

/**
 * Helper de Blade para generar URLs seguras (HTTPS) en producción.
 *
 * Garantiza que las URLs generadas utilicen el protocolo HTTPS
 * cuando la aplicación se ejecuta en entorno de producción.
 */
class SecureUrlHelper
{
    /**
     * Genera una URL de ruta con nombre forzando HTTPS en producción.
     *
     * @param  string  $route  Nombre de la ruta.
     * @param  mixed  $parameters  Parámetros de la ruta.
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
     * Genera una URL absoluta forzando HTTPS en producción.
     *
     * @param  string  $path  Ruta relativa.
     * @param  mixed  $parameters  Parámetros adicionales.
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
