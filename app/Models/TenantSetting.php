<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

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

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
