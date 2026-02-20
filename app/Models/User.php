<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasUlids, SoftDeletes;

    protected $connection = 'mysql';

    protected $fillable = [
        'name',
        'email',
        'password',
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
}
