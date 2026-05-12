<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa una nota personal del usuario.
 *
 * Almacenada en la base de datos del tenant.
 *
 * @property int $id
 * @property string $title
 * @property string|null $content
 * @property string $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 */
class Note extends Model
{
    protected $connection = 'tenant';

    protected $fillable = [
        'title',
        'content',
        'user_id',
    ];

    /**
     * Determina la conexión a usar según el contexto de tenancy.
     *
     * Intenta usar la conexión del tenant activo; si no existe contexto,
     * retorna la conexión por defecto.
     *
     * @return string
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

    /**
     * Obtiene el usuario propietario de la nota.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
