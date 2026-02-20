<?php

namespace App\Tenancy\Jobs;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class FinalizeProvisioning implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public function __construct(public Tenant|TenantWithDatabase $tenant) {}

    public function handle(): void
    {
        /** @var Tenant $tenant */
        $tenant = $this->tenant;

        $tenant->update(['status' => 'active']);
    }
}
