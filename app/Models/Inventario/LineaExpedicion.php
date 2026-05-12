<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa una línea de producto dentro de una expedición.
 *
 * @property int $id
 * @property int $id_expedicion
 * @property int $id_lote
 * @property int $id_producto
 * @property float $cantidad
 * @property string|null $unidad
 * @property string|null $estado
 * @property string|null $observaciones
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Expedicion $expedicion
 * @property-read Lote $lote
 * @property-read Producto $producto
 */
class LineaExpedicion extends Model
{
    protected $table = 'lineas_expedicion';

    protected $fillable = [
        'id_expedicion',
        'id_lote',
        'id_producto',
        'cantidad',
        'unidad',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'cantidad' => 'decimal:4',
    ];

    /**
     * Obtiene la expedición a la que pertenece la línea.
     */
    public function expedicion(): BelongsTo
    {
        return $this->belongsTo(Expedicion::class, 'id_expedicion', 'id_expedicion');
    }

    /**
     * Obtiene el lote de la línea.
     */
    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }

    /**
     * Obtiene el producto de la línea.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
