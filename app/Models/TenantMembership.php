<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

/**
 * Modelo que representa la relación de un usuario con un tenant.
 *
 * Define el rol, estado y permisos del usuario dentro de la organización.
 *
 * @property int $id
 * @property string $user_id
 * @property string $tenant_id
 * @property bool $is_owner
 * @property string|null $role
 * @property string|null $status
 * @property Carbon|null $joined_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string $role_label
 * @property-read string $status_label
 * @property-read User $user
 * @property-read Tenant $tenant
 */
class TenantMembership extends Model
{
    use CentralConnection;
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'is_owner',
        'role',
        'status',
        'joined_at',
    ];

    protected $casts = [
        'is_owner' => 'boolean',
        'joined_at' => 'datetime',
    ];

    /**
     * Obtiene la etiqueta legible del rol del usuario.
     */
    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Admin',
            'manager' => 'Manager',
            'empleado' => 'Empleado',
            default => ucfirst($this->role ?? ''),
        };
    }

    /**
     * Obtiene la etiqueta legible del estado de la membresía.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'active' => 'Activo',
            'pending' => 'Pendiente',
            'invited' => 'Pendiente',
            'disabled' => 'Deshabilitado',
            default => ucfirst($this->status ?? ''),
        };
    }

    /**
     * Obtiene el usuario de esta membresía.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene el tenant de esta membresía.
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
