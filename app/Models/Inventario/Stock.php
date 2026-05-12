<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa el stock de un producto en una ubicación y lote específicos.
 *
 * Utiliza clave primaria compuesta (sin auto-increment).
 *
 * @property int $id_producto
 * @property int $id_ubicacion
 * @property int|null $id_lote
 * @property float $cantidad_actual
 * @property float $cantidad_reservada
 * @property float|null $stock_minimo
 * @property float|null $stock_maximo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read float $disponible
 * @property-read Producto $producto
 * @property-read Ubicacion $ubicacion
 * @property-read Lote|null $lote
 */
class Stock extends Model
{
    protected $table = 'stock';

    // Composite primary key — set manually
    public $incrementing = false;

    protected $primaryKey = null;

    protected $fillable = [
        'id_producto',
        'id_ubicacion',
        'id_lote',
        'cantidad_actual',
        'cantidad_reservada',
        'stock_minimo',
        'stock_maximo',
    ];

    protected $casts = [
        'cantidad_actual' => 'decimal:4',
        'cantidad_reservada' => 'decimal:4',
        'stock_minimo' => 'decimal:4',
        'stock_maximo' => 'decimal:4',
    ];

    /**
     * Obtiene el producto asociado a este registro de stock.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    /**
     * Obtiene la ubicación física del stock.
     */
    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'id_ubicacion', 'id_ubicacion');
    }

    /**
     * Obtiene el lote asociado al stock.
     */
    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }

    /**
     * Calcula la cantidad disponible (actual menos reservada).
     */
    public function getDisponibleAttribute(): float
    {
        return max(0, (float) $this->cantidad_actual - (float) $this->cantidad_reservada);
    }
}
