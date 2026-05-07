<?php

namespace App\Http\Controllers;

use App\Actions\Tenancy\CreateTenantAction;
use App\Http\Requests\Company\StoreCompanyRequest;
use Illuminate\Http\JsonResponse;
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
     *
     * @return RedirectResponse|JsonResponse
     */
    public function store(StoreCompanyRequest $request, CreateTenantAction $createTenant)
    {
        $user = $request->user();

        if ($user->current_tenant_id) {
            $message = 'Ya tienes una empresa asignada.';

            if ($request->expectsJson()) {
                return response()->json(['redirect' => '/app', 'message' => $message], 200);
            }

            return redirect('/app')->with('info', $message);
        }

        try {
            $createTenant->execute($user, $request->validated());
        } catch (RuntimeException $e) {
            $error = 'Error al crear la empresa. Inténtalo de nuevo.';

            if ($request->expectsJson()) {
                return response()->json(['message' => $error], 422);
            }

            return back()->withErrors(['company_name' => $error]);
        }

        $message = 'Empresa creada correctamente.';

        if ($request->expectsJson()) {
            return response()->json(['redirect' => '/app', 'message' => $message], 200);
        }

        return redirect('/app')->with('success', $message);
    }
}
