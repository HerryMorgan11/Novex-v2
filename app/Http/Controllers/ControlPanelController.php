<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class ControlPanelController extends Controller
{
    /**
     * Muestra el panel de control con KPIs y datos reales del tenant actual.
     *
     * Los datos del tenant solo están disponibles si tenancy ha sido inicializado
     * (lo hace el middleware checkHasTenant). En caso contrario, devuelve defaults seguros.
     */
    public function index(): View
    {
        $tenant = $this->currentTenant();
        $users = $this->loadTenantUsers($tenant);
        $kpis = $this->buildKpis($tenant, $users->count());

        return view('dashboard.features.control-panel.controlPanelApp', [
            'users' => $users,
            'tenant' => $tenant,
            'kpis' => $kpis,
        ]);
    }

    private function currentTenant()
    {
        return (function_exists('tenant') && tenant()) ? tenant() : null;
    }

    private function loadTenantUsers($tenant)
    {
        if (! $tenant) {
            return collect();
        }

        return User::whereHas('memberships', fn ($q) => $q
            ->where('tenant_id', $tenant->id)
            ->where('status', 'active'))
            ->select('id', 'name', 'email', 'created_at')
            ->get();
    }

    private function buildKpis($tenant, int $usersCount): array
    {
        return [
            'total_users' => $usersCount,
            'new_users_this_month' => $this->newUsersThisMonth($tenant),
            'tenant_created_at' => $tenant?->created_at,
            'tenant_status' => $tenant?->status ?? 'unknown',
        ];
    }

    private function newUsersThisMonth($tenant): int
    {
        if (! $tenant) {
            return 0;
        }

        return User::whereHas('memberships', fn ($q) => $q
            ->where('tenant_id', $tenant->id)
            ->where('status', 'active'))
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();
    }
}
