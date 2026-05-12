<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

/**
 * Modelo que almacena la configuración personalizada de un tenant.
 *
 * @property int $id
 * @property string $tenant_id
 * @property string|null $timezone
 * @property string|null $locale
 * @property int|null $max_users
 * @property int|null $max_storage_gb
 * @property array|null $enabled_features
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Tenant $tenant
 */
class TenantSetting extends Model
{
    use CentralConnection;

    protected $fillable = [
        'tenant_id',
        'timezone',
        'locale',
        'max_users',
        'max_storage_gb',
        'enabled_features',
    ];

    protected $casts = [
        'enabled_features' => 'array',
    ];

    /**
     * Obtiene el tenant al que pertenece esta configuración.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
