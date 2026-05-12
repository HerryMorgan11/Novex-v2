<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa una línea de producto dentro de una recepción (transporte).
 *
 * @property int $id
 * @property int $id_recepcion
 * @property int|null $id_producto
 * @property int|null $id_lote
 * @property string|null $producto_codigo_ref
 * @property string|null $producto_nombre_ref
 * @property float $cantidad_esperada
 * @property float|null $cantidad_recibida
 * @property string|null $unidad
 * @property string|null $estado_linea
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Transporte $transporte
 * @property-read Producto|null $producto
 * @property-read Lote|null $lote
 */
class LineaTransporte extends Model
{
    protected $table = 'recepcion_productos';

    protected $fillable = [
        'id_recepcion',
        'id_producto',
        'id_lote',
        'producto_codigo_ref',
        'producto_nombre_ref',
        'cantidad_esperada',
        'cantidad_recibida',
        'unidad',
        'estado_linea',
    ];

    protected $casts = [
        'cantidad_esperada' => 'decimal:4',
        'cantidad_recibida' => 'decimal:4',
    ];

    /**
     * Obtiene el transporte (recepción) al que pertenece la línea.
     */
    public function transporte(): BelongsTo
    {
        return $this->belongsTo(Transporte::class, 'id_recepcion', 'id_recepcion');
    }

    /**
     * Obtiene el producto asociado a la línea.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    /**
     * Obtiene el lote asignado a la línea.
     */
    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }

    /**
     * Obtiene el nombre del producto, priorizando la referencia si es borrador.
     */
    public function nombreProducto(): string
    {
        if ($this->producto?->esBorrador() && $this->producto_nombre_ref) {
            return $this->producto_nombre_ref;
        }

        return $this->producto?->nombre ?? $this->producto_nombre_ref ?? 'Producto desconocido';
    }

    /**
     * Obtiene el código del producto, priorizando la referencia si es borrador.
     */
    public function codigoProducto(): string
    {
        if ($this->producto?->esBorrador() && $this->producto_codigo_ref) {
            return $this->producto_codigo_ref;
        }

        return $this->producto?->sku ?? $this->producto_codigo_ref ?? '-';
    }
}
