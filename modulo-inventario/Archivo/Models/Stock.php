<?php

namespace Modules\ModuloInventario\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;

    protected $table = 'stock';
    public $incrementing = false;
    protected $primaryKey = ['id_producto', 'id_ubicacion'];

    protected $fillable = [
        'id_producto',
        'id_ubicacion',
        'cantidad_actual',
        'cantidad_reservada',
        'stock_minimo',
        'stock_maximo',
    ];

    public function producto()
    {
        return $this->belongsTo(ProductoInventario::class, 'id_producto', 'id_producto');
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'id_ubicacion', 'id_ubicacion');
    }
}
