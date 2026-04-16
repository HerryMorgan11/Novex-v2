<?php

namespace Modules\ModuloInventario\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Serie extends Model
{
    use HasFactory;

    protected $table = 'series';
    protected $primaryKey = 'id_serie';

    protected $fillable = ['id_producto', 'numero_serie', 'estado'];

    public function producto()
    {
        return $this->belongsTo(ProductoInventario::class, 'id_producto', 'id_producto');
    }
}
