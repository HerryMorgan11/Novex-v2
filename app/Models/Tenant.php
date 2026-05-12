<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

/**
 * Modelo que representa un tenant (empresa/organización) en la plataforma.
 *
 * Cada tenant dispone de su propia base de datos aislada.
 *
 * @property string $id
 * @property string $name
 * @property string|null $slug
 * @property string $status
 * @property string|null $db_name
 * @property string|null $created_by_user_id
 * @property array|null $data
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read User|null $createdBy
 * @property-read Collection<int, Domain> $domains
 * @property-read Collection<int, TenantMembership> $memberships
 * @property-read TenantProvisioning|null $provisioning
 * @property-read TenantSetting|null $settings
 * @property-read Collection<int, TenantInvitation> $invitations
 * @property-read Collection<int, TenantAuditLog> $auditLogs
 */
class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasUuids, SoftDeletes;

    protected $connection = 'mysql';

    protected $fillable = [
        'id',
        'name',
        'slug',
        'status',
        'db_name',
        'created_by_user_id',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Devuelve las columnas personalizadas del tenant (no almacenadas en JSON 'data').
     *
     * @return array<int, string>
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'slug',
            'status',
            'db_name',
            'created_by_user_id',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    /**
     * Obtiene el usuario que creó este tenant.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    /**
     * Obtiene los dominios asociados al tenant.
     */
    public function domains(): HasMany
    {
        return $this->hasMany(Domain::class);
    }

    /**
     * Obtiene las membresías de usuarios en este tenant.
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(TenantMembership::class);
    }

    /**
     * Obtiene el registro de aprovisionamiento del tenant.
     */
    public function provisioning(): HasOne
    {
        return $this->hasOne(TenantProvisioning::class);
    }

    /**
     * Obtiene la configuración del tenant.
     */
    public function settings(): HasOne
    {
        return $this->hasOne(TenantSetting::class);
    }

    /**
     * Obtiene las invitaciones pendientes del tenant.
     */
    public function invitations(): HasMany
    {
        return $this->hasMany(TenantInvitation::class);
    }

    /**
     * Obtiene los registros de auditoría del tenant.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(TenantAuditLog::class);
    }

    /**
     * Obtiene el nombre de la base de datos del tenant.
     *
     * Si no tiene db_name asignado, genera uno basado en su ID.
     */
    public function getDatabaseName(): string
    {
        return $this->db_name ?: ('t_'.$this->id);
    }
}
