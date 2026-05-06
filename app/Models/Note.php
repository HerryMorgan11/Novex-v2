<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Note extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'title',
        'content',
        'user_id',
    ];

    /**
     * Determine which connection the model should use.
     * Falls back to default connection if tenant connection fails.
     */
    public function getConnectionName()
    {
        // Always try tenant connection first if we have a tenant context
        if (function_exists('tenant') && ! is_null(app('request')?->route())) {
            try {
                $tenantId = app('request')->route('tenant') ?? tenant()?->id;
                if ($tenantId) {
                    return 'tenant';
                }
            } catch (\Throwable) {
                // Fall back to default
            }
        }

        return parent::getConnectionName();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
