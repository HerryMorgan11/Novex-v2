<?php

namespace App\Livewire;

use App\Models\Tenant;
use App\Models\TenantMembership;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Stancl\Tenancy\Events\TenantCreated;
use Stancl\Tenancy\Jobs\CreateDatabase;
use Stancl\Tenancy\Jobs\MigrateDatabase;

class CreateCompanyModal extends Component
{
    public $company_name = '';

    public $industry = '';

    public $country = '';

    public $isSubmitting = false;

    public $showModal = true;

    protected $rules = [
        'company_name' => 'required|string|max:255',
        'industry' => 'required|string|max:255',
        'country' => 'required|string|max:255',
    ];

    protected $messages = [
        'company_name.required' => 'El nombre de la empresa es requerido.',
        'industry.required' => 'La industria es requerida.',
        'country.required' => 'El país es requerido.',
    ];

    public function mount()
    {
        $user = Auth::user();

        // Si ya tiene tenant, no mostrar modal
        if ($user && $user->current_tenant_id) {
            $this->showModal = false;
        } elseif ($user) {
            // Verificar si tiene algún membership activo
            $membership = $user->memberships()
                ->where('status', 'active')
                ->latest('id')
                ->first();

            if ($membership && $membership->tenant) {
                $this->showModal = false;
            } else {
                $this->showModal = true;
            }
        } else {
            $this->showModal = false;
        }
    }

    public function submit()
    {
        $this->validate();
        $this->isSubmitting = true;

        try {
            $user = Auth::user();

            // ⭐ CAPTURAR LOS VALORES AQUÍ - fuera de los closures
            $company_name = $this->company_name;
            $industry = $this->industry;
            $country = $this->country;

            // Transacción para crear tenant, membership y actualizar usuario
            $tenant = DB::transaction(function () use ($user, $company_name, $industry, $country) {
                // Crear el tenant con nombre de empresa e industria y país
                $tenant = Tenant::withoutEvents(function () use ($user, $company_name, $industry, $country) {
                    return Tenant::create([
                        'name' => $company_name,
                        'slug' => Str::slug($company_name).'-'.Str::lower(Str::random(6)),
                        'status' => 'provisioning',
                        'created_by_user_id' => $user->id,
                        'industry' => $industry,
                        'country' => $country,
                    ]);
                });

                // Crear membership como owner
                TenantMembership::create([
                    'user_id' => $user->id,
                    'tenant_id' => $tenant->id,
                    'is_owner' => true,
                    'status' => 'active',
                    'joined_at' => now(),
                ]);

                // Actualizar current_tenant_id del usuario
                $user->update(['current_tenant_id' => $tenant->id]);

                // Actualizar status a active
                $tenant->update(['status' => 'active']);

                return $tenant;
            });

            // Disparar evento de tenant creado (FUERA de la transacción)
            event(new TenantCreated($tenant));

            // Crear base de datos del tenant (FUERA de la transacción)
            try {
                app()->call([new CreateDatabase($tenant), 'handle']);
            } catch (\Exception $e) {
                \Log::warning('DB del tenant ya existía: '.$e->getMessage());
            }

            // Migrar base de datos del tenant (FUERA de la transacción)
            try {
                app()->call([new MigrateDatabase($tenant), 'handle']);
            } catch (\Exception $e) {
                \Log::error('Error migrando BD del tenant: '.$e->getMessage());
            }

            // Ocultar modal
            $this->showModal = false;

            // Redirigir al dashboard
            return redirect('/app');
        } catch (\Exception $e) {
            \Log::error('Error creando empresa: '.$e->getMessage());
            $this->addError('submit', 'Error al crear la empresa: '.$e->getMessage());
            $this->isSubmitting = false;
        }
    }

    public function render()
    {
        return view('livewire.create-company-modal');
    }
}
