<?php

namespace Modules\ModuloInventario\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RecepcionProducto extends Model
{
    use HasFactory;

    protected $table = 'recepcion_productos';

    protected $fillable = [
        'id_recepcion',
        'id_producto',
        'producto_codigo_ref',
        'producto_nombre_ref',
        'cantidad_esperada',
        'cantidad_recibida',
        'unidad'
    ];

    /**
     * Relación con la recepción
     */
    public function recepcion()
    {
        return $this->belongsTo(Recepcion::class, 'id_recepcion', 'id_recepcion');
    }

    /**
     * Relación con el producto maestro
     */
    public function producto()
    {
        return $this->belongsTo(ProductoInventario::class, 'id_producto', 'id_producto');
    }
}
