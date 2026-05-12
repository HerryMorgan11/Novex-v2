<?php

namespace App\Tenancy\Jobs;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

/**
 * Job que marca un tenant como 'active' tras completar su aprovisionamiento.
 *
 * Se ejecuta como último paso del pipeline de creación de tenant
 * (después de CreateDatabase y MigrateDatabase).
 */
class FinalizeProvisioning implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    /**
     * @param  Tenant|TenantWithDatabase  $tenant  Tenant a finalizar.
     */
    public function __construct(public Tenant|TenantWithDatabase $tenant) {}

    /**
     * Actualiza el estado del tenant a 'active'.
     */
    public function handle(): void
    {
        /** @var Tenant $tenant */
        $tenant = $this->tenant;

        $tenant->update(['status' => 'active']);
    }
}
