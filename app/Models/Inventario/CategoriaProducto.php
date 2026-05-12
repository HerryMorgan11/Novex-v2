<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa una categoría de producto.
 *
 * Soporta jerarquía padre-hijo para categorías anidadas.
 *
 * @property int $id_categoria
 * @property string $nombre
 * @property int|null $id_categoria_padre
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read CategoriaProducto|null $padre
 * @property-read Collection<int, CategoriaProducto> $hijos
 */
class CategoriaProducto extends Model
{
    protected $table = 'categorias_producto';

    protected $primaryKey = 'id_categoria';

    protected $fillable = ['nombre', 'id_categoria_padre'];

    /**
     * Obtiene la categoría padre.
     *
     * @return BelongsTo
     */
    public function padre()
    {
        return $this->belongsTo(self::class, 'id_categoria_padre', 'id_categoria');
    }

    /**
     * Obtiene las subcategorías hijas.
     *
     * @return HasMany
     */
    public function hijos()
    {
        return $this->hasMany(self::class, 'id_categoria_padre', 'id_categoria');
    }
}
