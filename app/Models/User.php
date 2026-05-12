<?php

namespace App\Models;

use App\Notifications\CustomResetPassword;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

/**
 * Modelo que representa un usuario registrado en la plataforma.
 *
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string|null $password
 * @property string|null $phone
 * @property string|null $dni
 * @property bool $is_active
 * @property string|null $current_tenant_id
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $last_login_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read TenantMembership|null $membership
 * @property-read Collection<int, TenantMembership> $memberships
 * @property-read Collection<int, Tenant> $createdTenants
 * @property-read Tenant|null $currentTenant
 * @property-read Collection<int, SocialAccount> $socialAccounts
 * @property-read Collection<int, TenantAuditLog> $auditLogs
 * @property-read Collection<int, ReminderList> $reminderLists
 * @property-read Collection<int, Reminder> $reminders
 */
class User extends Authenticatable
{
    use CentralConnection;
    use HasFactory, HasUlids, SoftDeletes;
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'dni',
        'is_active',
        'current_tenant_id',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /**
     * Obtiene la membresía principal del usuario.
     */
    public function membership(): HasOne
    {
        return $this->hasOne(TenantMembership::class, 'user_id');
    }

    /**
     * Obtiene todas las membresías del usuario en distintos tenants.
     */
    public function memberships(): HasMany
    {
        return $this->hasMany(TenantMembership::class, 'user_id');
    }

    /**
     * Obtiene los tenants creados por este usuario.
     */
    public function createdTenants(): HasMany
    {
        return $this->hasMany(Tenant::class, 'created_by_user_id');
    }

    /**
     * Obtiene el tenant activo del usuario.
     */
    public function currentTenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'current_tenant_id');
    }

    /**
     * Obtiene las cuentas sociales vinculadas (Google, etc.).
     */
    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * Obtiene los registros de auditoría del usuario.
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(TenantAuditLog::class);
    }

    /**
     * Obtiene las listas de recordatorios del usuario.
     */
    public function reminderLists(): HasMany
    {
        return $this->hasMany(ReminderList::class);
    }

    /**
     * Obtiene los recordatorios del usuario.
     */
    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    /**
     * Obtiene las etiquetas creadas por el usuario.
     */
    public function tags(): HasMany
    {
        return $this->hasMany(Tag::class);
    }

    /**
     * Indica si el usuario debe cambiar su contraseña antes de acceder al dashboard.
     * Se activa cuando tiene una membresía con status 'pending'.
     */
    public function requiresPasswordChange(): bool
    {
        return $this->memberships()
            ->whereIn('status', ['pending', 'invited'])
            ->exists();
    }

    /**
     * Devuelve el rol del usuario en su tenant actual.
     */
    public function roleInCurrentTenant(): ?string
    {
        if (! $this->current_tenant_id) {
            return null;
        }

        return $this->memberships()
            ->where('tenant_id', $this->current_tenant_id)
            ->value('role');
    }

    /**
     * Comprueba si el usuario es admin en su tenant actual.
     */
    public function isAdminInCurrentTenant(): bool
    {
        return $this->roleInCurrentTenant() === 'admin';
    }

    /**
     * Envía la notificación personalizada de restablecimiento de contraseña.
     *
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomResetPassword($token));
    }
}
