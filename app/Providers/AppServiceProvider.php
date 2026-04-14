<?php

namespace App\Providers;

use App\Models\Reminder;
use App\Models\ReminderList;
use App\Models\Subtask;
use App\Policies\ReminderListPolicy;
use App\Policies\ReminderPolicy;
use App\Policies\SubtaskPolicy;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Stancl\Tenancy\Facades\Tenancy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        $this->registerPolicies();
        $this->registerModelBindings();
    }

    /**
     * Vincula los modelos a sus resolvers personalizados para inicializar tenancy
     * automáticamente cuando se hace route model binding.
     */
    protected function registerModelBindings(): void
    {
        Route::bind('reminder', function ($id) {
            if (auth()->check() && auth()->user()->current_tenant_id) {
                Tenancy::initialize(auth()->user()->current_tenant_id);
            }

            return Reminder::findOrFail($id);
        });

        Route::bind('reminderList', function ($id) {
            if (auth()->check() && auth()->user()->current_tenant_id) {
                Tenancy::initialize(auth()->user()->current_tenant_id);
            }

            return ReminderList::findOrFail($id);
        });

        Route::bind('subtask', function ($id) {
            if (auth()->check() && auth()->user()->current_tenant_id) {
                Tenancy::initialize(auth()->user()->current_tenant_id);
            }

            return Subtask::findOrFail($id);
        });
    }

    /**
     * Registra las políticas de autorización de cada modelo.
     */
    protected function registerPolicies(): void
    {
        Gate::policy(ReminderList::class, ReminderListPolicy::class);
        Gate::policy(Reminder::class, ReminderPolicy::class);
        Gate::policy(Subtask::class, SubtaskPolicy::class);
    }

    /**
     * Configura los comportamientos por defecto de la aplicación.
     */
    protected function configureDefaults(): void
    {
        Date::use(CarbonImmutable::class);

        DB::prohibitDestructiveCommands(
            app()->isProduction(),
        );

        Password::defaults(fn (): ?Password => app()->isProduction()
            ? Password::min(12)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised()
            : null
        );
    }
}
