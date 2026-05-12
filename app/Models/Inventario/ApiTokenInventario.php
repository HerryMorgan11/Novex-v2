<?php

namespace App\Models\Inventario;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa un token de API para acceder al módulo de inventario.
 *
 * @property int $id
 * @property string $user_id
 * @property string $nombre
 * @property string $token
 * @property string|null $permisos
 * @property bool $activo
 * @property Carbon|null $ultimo_uso
 * @property Carbon|null $expira_en
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 */
class ApiTokenInventario extends Model
{
    protected $connection = 'tenant';

    protected $table = 'api_tokens_inventario';

    protected $fillable = [
        'user_id',
        'nombre',
        'token',
        'permisos',
        'activo',
        'ultimo_uso',
        'expira_en',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'ultimo_uso' => 'datetime',
        'expira_en' => 'datetime',
    ];

    protected $hidden = ['token'];

    /**
     * Obtiene el usuario propietario del token.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Determina si el token es válido (activo y no expirado).
     */
    public function esValido(): bool
    {
        return $this->activo
            && ($this->expira_en === null || $this->expira_en->isFuture());
    }
}
