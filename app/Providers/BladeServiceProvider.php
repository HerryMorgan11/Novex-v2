<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerBladeDirectives();
    }

    /**
     * Register custom Blade directives.
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
