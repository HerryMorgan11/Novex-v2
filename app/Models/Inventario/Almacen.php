<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa un almacén físico del inventario.
 *
 * @property int $id_almacen
 * @property string $nombre
 * @property string|null $direccion
 * @property string|null $responsable
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Zona> $zonas
 */
class Almacen extends Model
{
    protected $table = 'almacenes';

    protected $primaryKey = 'id_almacen';

    protected $fillable = ['nombre', 'direccion', 'responsable', 'activo'];

    protected $casts = ['activo' => 'boolean'];

    /**
     * Obtiene las zonas del almacén.
     */
    public function zonas(): HasMany
    {
        return $this->hasMany(Zona::class, 'id_almacen', 'id_almacen');
    }
}
