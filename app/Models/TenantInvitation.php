<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

/**
 * Modelo que representa una invitación pendiente a un tenant.
 *
 * @property int $id
 * @property string $tenant_id
 * @property string $email
 * @property string $token
 * @property string|null $status
 * @property string|null $invited_by_user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Tenant $tenant
 * @property-read User|null $invitedBy
 */
class TenantInvitation extends Model
{
    use CentralConnection;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'email',
        'token',
        'status',
        'invited_by_user_id',
    ];

    /**
     * Obtiene el tenant al que corresponde la invitación.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Obtiene el usuario que envió la invitación.
     */
    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by_user_id');
    }
}
