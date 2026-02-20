<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use App\Tenancy\Jobs\FinalizeProvisioning;
use Illuminate\Console\Command;
use Stancl\Tenancy\Jobs\CreateDatabase;
use Stancl\Tenancy\Jobs\MigrateDatabase;

class ProvisionTenant extends Command
{
    protected $signature = 'tenants:provision {id}';

    protected $description = 'Provision a specific tenant (create database and run migrations)';

    public function handle()
    {
        $tenantId = $this->argument('id');
        $tenant = Tenant::find($tenantId);

        if (! $tenant) {
            $this->error("Tenant not found: {$tenantId}");

            return 1;
        }

        $this->info("Provisioning tenant: {$tenant->name} ({$tenant->id})");

        try {
            $this->info('Creating database...');
            app()->call([new CreateDatabase($tenant), 'handle']);
            $this->info('✓ Database created');
        } catch (\Exception $e) {
            $this->warn('Database creation skipped: '.$e->getMessage());
            $this->info('(This is OK if the database already exists)');
        }

        try {
            $this->info('Running migrations...');
            // Initialize tenancy context so migration runs on the tenant database
            tenancy()->initialize($tenant);
            app()->call([new MigrateDatabase($tenant), 'handle']);
            tenancy()->end();
            $this->info('✓ Migrations complete');
        } catch (\Exception $e) {
            $this->error('Migrations failed: '.$e->getMessage());

            return 1;
        }

        try {
            $this->info('Finalizing provisioning...');
            app()->call([new FinalizeProvisioning($tenant), 'handle']);
            $this->info('✓ Tenant status set to active');
        } catch (\Exception $e) {
            $this->error('Finalize failed: '.$e->getMessage());

            return 1;
        }

        $tenant->refresh();
        $this->info('Tenant status: '.$tenant->status);
        $this->info('Tenant database: '.($tenant->db_name ?? $tenant->tenancy_db_name ?? 'tenant_'.$tenant->id));

        return 0;
    }
}
