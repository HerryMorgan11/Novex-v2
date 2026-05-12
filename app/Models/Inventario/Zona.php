<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa una zona dentro de un almacén.
 *
 * @property int $id_zona
 * @property int $id_almacen
 * @property string $nombre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Almacen $almacen
 * @property-read Collection<int, Estanteria> $estanterias
 */
class Zona extends Model
{
    protected $table = 'zonas';

    protected $primaryKey = 'id_zona';

    protected $fillable = ['id_almacen', 'nombre'];

    /**
     * Obtiene el almacén al que pertenece la zona.
     */
    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }

    /**
     * Obtiene las estanterías de la zona.
     */
    public function estanterias(): HasMany
    {
        return $this->hasMany(Estanteria::class, 'id_zona', 'id_zona');
    }
}
