<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Stancl\Tenancy\Database\Concerns\CentralConnection;

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

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
