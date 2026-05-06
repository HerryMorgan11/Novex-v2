<?php

namespace App\Models;

use App\Notifications\CustomResetPassword;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

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

    public function membership(): HasOne
    {
        return $this->hasOne(TenantMembership::class, 'user_id');
    }

    public function memberships(): HasMany
    {
        return $this->hasMany(TenantMembership::class, 'user_id');
    }

    public function createdTenants(): HasMany
    {
        return $this->hasMany(Tenant::class, 'created_by_user_id');
    }

    public function currentTenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'current_tenant_id');
    }

    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(TenantAuditLog::class);
    }

    public function reminderLists(): HasMany
    {
        return $this->hasMany(ReminderList::class);
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

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

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomResetPassword($token));
    }
}
