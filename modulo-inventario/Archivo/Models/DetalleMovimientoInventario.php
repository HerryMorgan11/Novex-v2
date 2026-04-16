<?php

namespace Modules\ModuloInventario\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetalleMovimientoInventario extends Model
{
    use HasFactory;

    protected $table = 'detalle_movimientos_inventario';

    protected $fillable = [
        'id_movimiento',
        'id_producto',
        'id_ubicacion_origen',
        'id_ubicacion_destino',
        'cantidad',
        'costo_unitario',
    ];

    public function movimiento()
    {
        return $this->belongsTo(MovimientoInventario::class, 'id_movimiento', 'id_movimiento');
    }

    public function producto()
    {
        return $this->belongsTo(ProductoInventario::class, 'id_producto', 'id_producto');
    }
}
