<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Blade;

class SecureRoute
{
    /**
     * Register the Blade directive for secure routes.
     * Usage in Blade: {{ secure_route('login') }} or secure_action('action.name')
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
