<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

/**
 * Registra directivas personalizadas de Blade.
 *
 * Proporciona `@secureRoute` y `@secureUrl` para generar URLs HTTPS en producción.
 */
class BladeServiceProvider extends ServiceProvider
{
    /**
     * Arranca las directivas de Blade.
     */
    public function boot(): void
    {
        $this->registerBladeDirectives();
    }

    /**
     * Registra las directivas `@secureRoute` y `@secureUrl`.
     */
    private function registerBladeDirectives(): void
    {
        // Directive for secure routes - forces HTTPS in production
        Blade::directive('secureRoute', function ($expression) {
            return "<?php echo \\App\\Helpers\\SecureUrlHelper::secureRoute($expression); ?>";
        });

        // Directive for secure URLs - forces HTTPS in production
        Blade::directive('secureUrl', function ($expression) {
            return "<?php echo \\App\\Helpers\\SecureUrlHelper::secureUrl($expression); ?>";
        });
    }
}
