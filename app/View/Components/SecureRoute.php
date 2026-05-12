<?php

namespace App\View\Components;

use App\Providers\BladeServiceProvider;
use Illuminate\Support\Facades\Blade;

/**
 * Registra directivas Blade para generar URLs seguras (HTTPS) en producción.
 *
 * @deprecated Usar {@see BladeServiceProvider} que ya registra las mismas directivas.
 */
class SecureRoute
{
    /**
     * Registra las directivas `@secureRoute` y `@secureUrl` en Blade.
     */
    public static function register(): void
    {
        Blade::directive('secureRoute', function ($expression) {
            return "<?php echo app('App\\\Helpers\\\SecureUrlHelper')::secureRoute($expression); ?>";
        });

        Blade::directive('secureUrl', function ($expression) {
            return "<?php echo app('App\\\Helpers\\\SecureUrlHelper')::secureUrl($expression); ?>";
        });
    }
}
