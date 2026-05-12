<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa una estantería dentro de una zona del almacén.
 *
 * @property int $id_estanteria
 * @property int $id_almacen
 * @property int|null $id_zona
 * @property string $codigo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Almacen $almacen
 * @property-read Zona|null $zona
 * @property-read Collection<int, Ubicacion> $ubicaciones
 */
class Estanteria extends Model
{
    protected $table = 'estanterias';

    protected $primaryKey = 'id_estanteria';

    protected $fillable = ['id_almacen', 'id_zona', 'codigo'];

    /**
     * Obtiene el almacén al que pertenece la estantería.
     */
    public function almacen(): BelongsTo
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }

    /**
     * Obtiene la zona a la que pertenece la estantería.
     */
    public function zona(): BelongsTo
    {
        return $this->belongsTo(Zona::class, 'id_zona', 'id_zona');
    }

    /**
     * Obtiene las ubicaciones de la estantería.
     */
    public function ubicaciones(): HasMany
    {
        return $this->hasMany(Ubicacion::class, 'id_estanteria', 'id_estanteria');
    }
}
