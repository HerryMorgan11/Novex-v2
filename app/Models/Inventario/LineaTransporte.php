<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function transporte(): BelongsTo
    {
        return $this->belongsTo(Transporte::class, 'id_recepcion', 'id_recepcion');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }

    public function nombreProducto(): string
    {
        return $this->producto?->nombre ?? $this->producto_nombre_ref ?? 'Producto desconocido';
    }

    public function codigoProducto(): string
    {
        return $this->producto?->sku ?? $this->producto_codigo_ref ?? '-';
    }
}
