<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Tenancy\Jobs\FinalizeProvisioning;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Stancl\Tenancy\Jobs\CreateDatabase;
use Stancl\Tenancy\Jobs\MigrateDatabase;

/**
 * Pantalla de espera durante el provisioning del tenant + endpoint de polling.
 *
 * El front consulta `status()` periódicamente hasta recibir status = 'active'.
 * Si detectamos que el pipeline quedó a medias (status = provisioning sin jobs
 * en cola), relanzamos síncronamente para evitar quedar bloqueados.
 */
class ProvisioningController extends Controller
{
    public function page(): View
    {
        return view('auth.provisioning');
    }

    public function status(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (! $user) {
            return response()->json(['status' => 'guest'], 401);
        }

        $tenant = $user->currentTenant ?? $user->membership?->tenant;

        if (! $tenant) {
            return response()->json(['status' => 'no-tenant']);
        }

        if ($this->pipelineStalled($tenant)) {
            $this->runPipelineSynchronously($tenant);
            $tenant->refresh();
        }

        return response()->json([
            'status' => $tenant->status,
            'db_name' => $tenant->db_name ?? $tenant->tenancy_db_name ?? null,
        ]);
    }

    /**
     * Un pipeline se considera atascado si sigue en 'provisioning' y no hay jobs pendientes.
     */
    private function pipelineStalled(Tenant $tenant): bool
    {
        return $tenant->status === 'provisioning' && DB::table('jobs')->count() === 0;
    }

    /**
     * Último recurso: ejecuta los jobs síncronamente para desatascar un provisioning.
     * CreateDatabase puede fallar si la BD ya existe; lo consideramos no-op.
     */
    private function runPipelineSynchronously(Tenant $tenant): void
    {
        try {
            (new CreateDatabase($tenant))->handle();
        } catch (\Throwable) {
            // La BD ya existe — continuamos con la migración.
        }

        (new MigrateDatabase($tenant))->handle();
        (new FinalizeProvisioning($tenant))->handle();
    }
}
