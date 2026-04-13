<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\TenantMembership;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Stancl\Tenancy\Events\TenantCreated;
use Stancl\Tenancy\Jobs\CreateDatabase;
use Stancl\Tenancy\Jobs\MigrateDatabase;

class CompanyController extends Controller
{
    /**
     * Crea una nueva empresa (tenant) para el usuario autenticado.
     * Provisiona la base de datos y redirige al dashboard.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'industry' => ['required', 'string', 'max:255'],
            'country' => ['required', 'string', 'max:255'],
        ]);

        $user = Auth::user();

        // Crear tenant, membership y asignar al usuario en una sola transacción
        $tenant = DB::transaction(function () use ($user, $data) {
            $tenant = Tenant::withoutEvents(function () use ($user, $data) {
                return Tenant::create([
                    'name' => $data['company_name'],
                    'slug' => Str::slug($data['company_name']).'-'.Str::lower(Str::random(6)),
                    'status' => 'provisioning',
                    'created_by_user_id' => $user->id,
                    'industry' => $data['industry'],
                    'country' => $data['country'],
                ]);
            });

            TenantMembership::create([
                'user_id' => $user->id,
                'tenant_id' => $tenant->id,
                'is_owner' => true,
                'status' => 'active',
                'joined_at' => now(),
            ]);

            $user->update(['current_tenant_id' => $tenant->id]);
            $tenant->update(['status' => 'active']);

            return $tenant;
        });

        // Evento y provisioning de BD fuera de la transacción para evitar bloqueos
        event(new TenantCreated($tenant));

        try {
            app()->call([new CreateDatabase($tenant), 'handle']);
        } catch (\Exception $e) {
            // La BD puede ya existir en caso de reintento — no es error fatal
            Log::warning('BD del tenant ya existía: '.$e->getMessage());
        }

        try {
            app()->call([new MigrateDatabase($tenant), 'handle']);
        } catch (\Exception $e) {
            Log::error('Error migrando BD del tenant: '.$e->getMessage());

            return back()->withErrors(['company_name' => 'Error al crear la empresa. Inténtalo de nuevo.']);
        }

        return redirect('/app')->with('success', 'Empresa creada correctamente.');
    }
}
