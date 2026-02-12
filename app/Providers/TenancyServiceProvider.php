<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Stancl\JobPipeline\JobPipeline;
use Stancl\Tenancy\Events;
use Stancl\Tenancy\Jobs;
use Stancl\Tenancy\Listeners;
use Stancl\Tenancy\Middleware;

class TenancyServiceProvider extends ServiceProvider
{
    /**
     * By default, no namespace is used to support the callable array syntax.
     */
    public static string $controllerNamespace = '';

    /**
     * Eventos/listeners del paquete.
     * - Provisioning en cola (async) => para pantalla "preparando la cuenta".
     * - NO borres DB en TenantDeleted (usas soft delete; el purge se hace aparte).
     */
    public function events(): array
    {
        return [
            // Tenant lifecycle
            Events\TenantCreated::class => [
                JobPipeline::make([
                    Jobs\CreateDatabase::class,
                    Jobs\MigrateDatabase::class,
                    // Jobs\SeedDatabase::class, // activar cuando tengas migraciones/seed tenant
                    // \App\Tenancy\Jobs\FinalizeProvisioning::class, // opcional
                ])
                    ->send(fn (Events\TenantCreated $event) => $event->tenant)
                    ->shouldBeQueued(true),
            ],

            // Tenancy bootstrapping
            Events\TenancyInitialized::class => [
                Listeners\BootstrapTenancy::class,
            ],
            Events\TenancyEnded::class => [
                Listeners\RevertToCentralContext::class,
            ],

            // Resource syncing (si no lo usas, lo puedes quitar)
            Events\SyncedResourceSaved::class => [
                Listeners\UpdateSyncedResource::class,
            ],

            // Resto vacío (sin listeners)
            Events\CreatingTenant::class => [],
            Events\SavingTenant::class => [],
            Events\TenantSaved::class => [],
            Events\UpdatingTenant::class => [],
            Events\TenantUpdated::class => [],
            Events\DeletingTenant::class => [],
            Events\TenantDeleted::class => [
                // ⚠️ NO recomendado con soft delete:
                // JobPipeline::make([Jobs\DeleteDatabase::class])
                //     ->send(fn (Events\TenantDeleted $event) => $event->tenant)
                //     ->shouldBeQueued(true),
            ],

            // Domain events
            Events\CreatingDomain::class => [],
            Events\DomainCreated::class => [],
            Events\SavingDomain::class => [],
            Events\DomainSaved::class => [],
            Events\UpdatingDomain::class => [],
            Events\DomainUpdated::class => [],
            Events\DeletingDomain::class => [],
            Events\DomainDeleted::class => [],

            // Database events (por si quieres engancharte luego)
            Events\DatabaseCreated::class => [],
            Events\DatabaseMigrated::class => [],
            Events\DatabaseSeeded::class => [],
            Events\DatabaseRolledBack::class => [],
            Events\DatabaseDeleted::class => [],

            // Other tenancy events
            Events\InitializingTenancy::class => [],
            Events\EndingTenancy::class => [],
            Events\BootstrappingTenancy::class => [],
            Events\TenancyBootstrapped::class => [],
            Events\RevertingToCentralContext::class => [],
            Events\RevertedToCentralContext::class => [],
            Events\SyncedResourceChangedInForeignDatabase::class => [],
        ];
    }

    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->bootEvents();
        $this->mapRoutes();
        $this->makeTenancyMiddlewareHighestPriority();
    }

    protected function bootEvents(): void
    {
        foreach ($this->events() as $event => $listeners) {
            foreach ($listeners as $listener) {
                if ($listener instanceof JobPipeline) {
                    $listener = $listener->toListener();
                }
                Event::listen($event, $listener);
            }
        }
    }

    /**
     * Carga rutas TENANT únicamente, protegidas por tenancy middlewares.
     * Estas rutas NO deben cargarse en el dominio central.
     *
     * IMPORTANTE: Cambia InitializeTenancyByDomain según tu estrategia:
     *   - ByDomain: empresa1.com, empresa2.com (dominios completos)
     *   - BySubdomain: empresa1.miapp.com, empresa2.miapp.com
     *   - ByDomainOrSubdomain: ambos casos
     */
    protected function mapRoutes(): void
    {
        $this->app->booted(function () {
            if (! file_exists(base_path('routes/tenant.php'))) {
                return;
            }

            Route::namespace(static::$controllerNamespace)
                ->middleware([
                    'web',
                    Middleware\PreventAccessFromCentralDomains::class,
                    Middleware\InitializeTenancyByDomain::class, // ✅ dominio completo
                    // Alternativas:
                    // Middleware\InitializeTenancyBySubdomain::class,
                    // Middleware\InitializeTenancyByDomainOrSubdomain::class,
                ])
                ->group(base_path('routes/tenant.php'));
        });
    }

    /**
     * Prioridad de middlewares: tenancy lo más arriba posible.
     * Evita que bindings/auth/etc. corran antes de fijar el tenant.
     */
    protected function makeTenancyMiddlewareHighestPriority(): void
    {
        $tenancyMiddleware = [
            Middleware\PreventAccessFromCentralDomains::class,
            Middleware\InitializeTenancyByDomain::class, // ✅ consistente con mapRoutes()

            // Mantener comentados para evitar inicializaciones accidentales:
            // Middleware\InitializeTenancyBySubdomain::class,
            // Middleware\InitializeTenancyByDomainOrSubdomain::class,
            // Middleware\InitializeTenancyByPath::class,
            // Middleware\InitializeTenancyByRequestData::class,
        ];

        // ✅ Pedimos la implementación real (HttpKernel), no la interfaz
        /** @var HttpKernel $kernel */
        $kernel = $this->app->make(HttpKernel::class);

        // ✅ Evita errores si cambias kernel/custom kernel en el futuro
        if (! method_exists($kernel, 'prependToMiddlewarePriority')) {
            return;
        }

        foreach (array_reverse($tenancyMiddleware) as $middleware) {
            $kernel->prependToMiddlewarePriority($middleware);
        }
    }
}
