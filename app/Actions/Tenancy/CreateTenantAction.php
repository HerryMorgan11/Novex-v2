<?php

namespace App\Actions\Tenancy;

use App\Models\Tenant;
use App\Models\TenantMembership;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use Stancl\Tenancy\Jobs\CreateDatabase;
use Stancl\Tenancy\Jobs\MigrateDatabase;

/**
 * Crea una nueva empresa (tenant) para un usuario y provisiona su base de datos
 * en modo síncrono.
 *
 * Responsabilidades:
 *  1. Persistencia transaccional de Tenant + TenantMembership + asignación al usuario.
 *  2. Provisioning síncrono: CreateDatabase + MigrateDatabase.
 *  3. Marcar el tenant como 'active' sólo si el provisioning ha sido correcto.
 *
 * Se usa `withoutEvents` al crear el Tenant para evitar que el pipeline queued
 * de Stancl Tenancy se dispare en paralelo y duplique el provisioning.
 */
class CreateTenantAction
{
    /**
     * @param  array{company_name:string, industry:string, country:string}  $data
     *
     * @throws RuntimeException si la migración de la BD del tenant falla.
     */
    public function execute(User $user, array $data): Tenant
    {
        $tenant = DB::transaction(function () use ($user, $data) {
            $tenant = Tenant::withoutEvents(fn () => Tenant::create([
                'name' => $data['company_name'],
                'slug' => Str::slug($data['company_name']).'-'.Str::lower(Str::random(6)),
                'status' => 'provisioning',
                'created_by_user_id' => $user->id,
                'industry' => $data['industry'],
                'country' => $data['country'],
            ]));

            TenantMembership::create([
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'is_owner' => true,
                'status' => 'active',
                'joined_at' => now(),
            ]);

            $user->update(['current_tenant_id' => $tenant->id]);

            return $tenant;
        });

        $this->provisionDatabase($tenant);

        $tenant->update(['status' => 'active']);

        return $tenant;
    }

    private function provisionDatabase(Tenant $tenant): void
    {
        try {
            app()->call([new CreateDatabase($tenant), 'handle']);
        } catch (\Exception $e) {
            // La BD puede existir en caso de reintento — no es error fatal.
            Log::warning('BD del tenant ya existía: '.$e->getMessage());
        }

        try {
            app()->call([new MigrateDatabase($tenant), 'handle']);
        } catch (\Exception $e) {
            Log::error('Error migrando BD del tenant: '.$e->getMessage());

            throw new RuntimeException('No se pudo migrar la BD del tenant.', previous: $e);
        }
    }
}
