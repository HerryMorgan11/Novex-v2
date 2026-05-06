<?php

namespace App\Http\Controllers;

use App\Actions\Tenancy\CreateTenantAction;
use App\Http\Requests\Company\StoreCompanyRequest;
use Illuminate\Http\RedirectResponse;
use RuntimeException;

class CompanyController extends Controller
{
    /**
     * Crea una nueva empresa (tenant) para el usuario autenticado.
     *
     * Delegación completa a CreateTenantAction: este controlador sólo hace de glue
     * HTTP entre la request validada y la acción de dominio.
     *
     * Idempotencia: si el usuario ya tiene un tenant asignado, no se crea uno nuevo.
     */
    public function store(StoreCompanyRequest $request, CreateTenantAction $createTenant): RedirectResponse
    {
        $user = $request->user();

        if ($user->current_tenant_id) {
            return redirect('/app')->with('info', 'Ya tienes una empresa asignada.');
        }

        try {
            $createTenant->execute($user, $request->validated());
        } catch (RuntimeException $e) {
            return back()->withErrors(['company_name' => 'Error al crear la empresa. Inténtalo de nuevo.']);
        }

        return redirect('/app')->with('success', 'Empresa creada correctamente.');
    }
}
