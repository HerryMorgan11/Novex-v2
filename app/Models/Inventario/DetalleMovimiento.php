<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa una línea de detalle de un movimiento de inventario.
 *
 * @property int $id
 * @property int $id_movimiento
 * @property int $id_producto
 * @property int|null $id_lote
 * @property int|null $id_ubicacion_origen
 * @property int|null $id_ubicacion_destino
 * @property float $cantidad
 * @property float|null $costo_unitario
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Producto $producto
 * @property-read Lote|null $lote
 * @property-read Ubicacion|null $ubicacionOrigen
 * @property-read Ubicacion|null $ubicacionDestino
 */
class DetalleMovimiento extends Model
{
    protected $table = 'detalle_movimientos_inventario';

    protected $fillable = [
        'id_movimiento',
        'id_producto',
        'id_lote',
        'id_ubicacion_origen',
        'id_ubicacion_destino',
        'cantidad',
        'costo_unitario',
    ];

    protected $casts = [
        'cantidad' => 'decimal:4',
        'costo_unitario' => 'decimal:2',
    ];

    /**
     * Obtiene el producto del detalle.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    /**
     * Obtiene el lote del detalle.
     */
    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }

    /**
     * Obtiene la ubicación de origen del movimiento.
     */
    public function ubicacionOrigen(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'id_ubicacion_origen', 'id_ubicacion');
    }

    /**
     * Obtiene la ubicación de destino del movimiento.
     */
    public function ubicacionDestino(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'id_ubicacion_destino', 'id_ubicacion');
    }
}
