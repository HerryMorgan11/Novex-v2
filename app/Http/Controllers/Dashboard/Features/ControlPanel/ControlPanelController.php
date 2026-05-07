<?php

namespace App\Http\Controllers\Dashboard\Features\ControlPanel;

use App\Http\Controllers\Controller;
use App\Models\Inventario\ApiTokenInventario;
use App\Models\User;
use Illuminate\Support\Str;
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
            'apiToken' => $this->loadApiToken(),
        ]);
    }

    private function currentTenant()
    {
        return (function_exists('tenant') && tenant()) ? tenant() : null;
    }

    private function loadApiToken(): ?string
    {
        if (! $this->currentTenant()) {
            return null;
        }

        $user = auth()->user();
        if (! $user) {
            return null;
        }

        // Buscar un token activo existente para este usuario
        $token = ApiTokenInventario::where('user_id', $user->id)
            ->where('activo', true)
            ->where(function ($q) {
                $q->whereNull('expira_en')->orWhere('expira_en', '>', now());
            })
            ->first();

        // Si no existe, generar uno nuevo automáticamente
        if (! $token) {
            $plainToken = Str::random(64);
            $token = ApiTokenInventario::create([
                'user_id' => $user->id,
                'nombre' => "Token API - {$user->name}",
                'token' => hash('sha256', $plainToken),
                'permisos' => 'full',
                'activo' => true,
            ]);

            // Devolver el token en texto plano (solo una vez)
            return $plainToken;
        }

        return $token->makeVisible('token')->token;
    }

    private function loadTenantUsers($tenant)
    {
        if (! $tenant) {
            return collect();
        }

        return User::whereHas('memberships', fn ($q) => $q->where('tenant_id', $tenant->id))
            ->with(['memberships' => fn ($q) => $q->where('tenant_id', $tenant->id)])
            ->select('id', 'name', 'email', 'created_at')
            ->get()
            ->map(function (User $user) {
                $membership = $user->memberships->first();
                $user->role = $membership?->role_label ?? '—';
                $user->status = $membership?->status_label ?? '—';
                $user->raw_status = $membership?->status ?? '';
                $user->raw_role = $membership?->role ?? '';
                $user->is_owner = (bool) ($membership?->is_owner ?? false);

                return $user;
            });
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

        return User::whereHas('memberships', fn ($q) => $q->where('tenant_id', $tenant->id))
            ->where('created_at', '>=', now()->startOfMonth())
            ->count();
    }
}
