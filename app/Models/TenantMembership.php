<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

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

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'Admin',
            'manager' => 'Manager',
            'empleado' => 'Empleado',
            default => ucfirst($this->role ?? ''),
        };
    }

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
