<?php

namespace App\Providers;

use App\Models\Reminder;
use App\Models\ReminderList;
use App\Models\Subtask;
use App\Models\Tag;
use App\Policies\ReminderListPolicy;
use App\Policies\ReminderPolicy;
use App\Policies\SubtaskPolicy;
use App\Policies\TagPolicy;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

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

    protected function registerModelBindings(): void
    {
        \Illuminate\Support\Facades\Route::bind('reminder', function ($id) {
            if (auth()->check() && auth()->user()->current_tenant_id) {
                \Stancl\Tenancy\Facades\Tenancy::initialize(auth()->user()->current_tenant_id);
            }

            return Reminder::findOrFail($id);
        });

        \Illuminate\Support\Facades\Route::bind('reminderList', function ($id) {
            if (auth()->check() && auth()->user()->current_tenant_id) {
                \Stancl\Tenancy\Facades\Tenancy::initialize(auth()->user()->current_tenant_id);
            }

            return ReminderList::findOrFail($id);
        });

        \Illuminate\Support\Facades\Route::bind('tag', function ($id) {
            if (auth()->check() && auth()->user()->current_tenant_id) {
                \Stancl\Tenancy\Facades\Tenancy::initialize(auth()->user()->current_tenant_id);
            }

            return Tag::findOrFail($id);
        });

        \Illuminate\Support\Facades\Route::bind('subtask', function ($id) {
            if (auth()->check() && auth()->user()->current_tenant_id) {
                \Stancl\Tenancy\Facades\Tenancy::initialize(auth()->user()->current_tenant_id);
            }

            return Subtask::findOrFail($id);
        });
    }

    protected function registerPolicies(): void
    {
        Gate::policy(ReminderList::class, ReminderListPolicy::class);
        Gate::policy(Reminder::class, ReminderPolicy::class);
        Gate::policy(Subtask::class, SubtaskPolicy::class);
        Gate::policy(Tag::class, TagPolicy::class);
    }

    /**
     * Configure default behaviors for production-ready applications.
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
