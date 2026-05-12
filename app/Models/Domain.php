<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Stancl\Tenancy\Database\Models\Domain as BaseDomain;

/**
 * Modelo que representa un dominio asociado a un tenant.
 *
 * @property int $id
 * @property string $domain
 * @property string $tenant_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Tenant $tenant
 */
class Domain extends BaseDomain
{
    protected $fillable = [
        'domain',
        'tenant_id',
    ];

    /**
     * Obtiene el tenant propietario del dominio.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
