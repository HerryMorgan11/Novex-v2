<?php

namespace App\Http\Controllers\Dashboard\Features\ControlPanel;

use App\Actions\Tenancy\CreateTenantUserAction;
use App\Http\Controllers\Controller;
use App\Models\TenantMembership;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserManagementController extends Controller
{
    /**
     * Muestra la lista de usuarios del tenant (reutiliza la vista del panel de control).
     * Se accede desde AJAX / navegación del panel, no directamente.
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('controlpanel.home');
    }

    /**
     * Crea un nuevo usuario en el tenant actual del administrador.
     * Devuelve JSON con los datos del usuario y la contraseña provisional.
     */
    public function store(Request $request, CreateTenantUserAction $action): JsonResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('mysql.users', 'email')],
            'role' => ['required', Rule::in(['admin', 'manager', 'empleado'])],
        ]);

        $tenant = $this->currentTenant();

        if (! $tenant) {
            return response()->json(['message' => 'No hay tenant activo.'], 422);
        }

        $result = $action->execute($tenant, $validated);

        return response()->json([
            'message' => 'Usuario creado correctamente.',
            'user' => [
                'id' => $result['user']->id,
                'name' => $result['user']->name,
                'email' => $result['user']->email,
                'role' => $validated['role'],
                'role_label' => match ($validated['role']) {
                    'admin' => 'Admin',
                    'manager' => 'Manager',
                    'empleado' => 'Empleado',
                },
                'status' => 'pending',
                'status_label' => 'Pendiente',
                'created_at' => $result['user']->created_at->format('d/m/Y'),
            ],
            'plain_password' => $result['plain_password'],
        ], 201);
    }

    /**
     * Actualiza el rol o estado de un usuario en el tenant actual.
     */
    public function update(Request $request, string $userId): JsonResponse
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'role' => ['sometimes', Rule::in(['admin', 'manager', 'empleado'])],
            'status' => ['sometimes', Rule::in(['active', 'disabled'])],
        ]);

        $tenant = $this->currentTenant();

        $membership = TenantMembership::on('central')
            ->where('user_id', $userId)
            ->where('tenant_id', $tenant->id)
            ->firstOrFail();

        // Proteger al propietario del tenant
        if ($membership->is_owner) {
            return response()->json(['message' => 'No se puede modificar al propietario del tenant.'], 403);
        }

        $membership->update($validated);

        return response()->json(['message' => 'Usuario actualizado correctamente.']);
    }

    /**
     * Elimina (soft delete) un usuario del tenant.
     */
    public function destroy(string $userId): JsonResponse
    {
        $this->authorizeAdmin();

        $tenant = $this->currentTenant();

        $membership = TenantMembership::on('central')
            ->where('user_id', $userId)
            ->where('tenant_id', $tenant->id)
            ->firstOrFail();

        if ($membership->is_owner) {
            return response()->json(['message' => 'No se puede eliminar al propietario del tenant.'], 403);
        }

        // Eliminar membresía y desasociar el tenant del usuario
        $membership->delete();

        User::on('central')
            ->where('id', $userId)
            ->where('current_tenant_id', $tenant->id)
            ->update(['current_tenant_id' => null]);

        return response()->json(['message' => 'Usuario eliminado del tenant correctamente.']);
    }

    private function authorizeAdmin(): void
    {
        abort_if(! Auth::user()?->isAdminInCurrentTenant(), 403, 'No tienes permisos para realizar esta acción.');
    }

    private function currentTenant()
    {
        return (function_exists('tenant') && tenant()) ? tenant() : null;
    }
}
