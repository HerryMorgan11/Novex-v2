<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

/**
 * Modelo que registra el estado del proceso de aprovisionamiento de un tenant.
 *
 * Permite rastrear el progreso de creación de la base de datos y migración.
 *
 * @property int $id
 * @property string $tenant_id
 * @property string $status
 * @property string|null $step
 * @property int $progress
 * @property string|null $error_message
 * @property Carbon|null $started_at
 * @property Carbon|null $finished_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Tenant $tenant
 */
class TenantProvisioning extends Model
{
    use CentralConnection;

    protected $fillable = [
        'tenant_id',
        'status',
        'step',
        'progress',
        'error_message',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'progress' => 'integer',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    /**
     * Obtiene el tenant asociado a este aprovisionamiento.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
