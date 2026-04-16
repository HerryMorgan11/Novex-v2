<?php

namespace Modules\ModuloInventario\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetalleInventarioFisico extends Model
{
    use HasFactory;

    protected $table = 'detalle_inventarios_fisicos';

    protected $fillable = [
        'id_inventario',
        'id_producto',
        'id_ubicacion',
        'cantidad_sistema',
        'cantidad_fisica',
        'diferencia',
    ];

    public function inventarioBase()
    {
        return $this->belongsTo(InventarioFisico::class, 'id_inventario', 'id_inventario');
    }

    public function producto()
    {
        return $this->belongsTo(ProductoInventario::class, 'id_producto', 'id_producto');
    }
}
