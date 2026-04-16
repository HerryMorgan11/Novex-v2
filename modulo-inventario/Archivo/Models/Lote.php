<?php

namespace Modules\ModuloInventario\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Lote extends Model
{
    use HasFactory;

    protected $table = 'lotes';
    protected $primaryKey = 'id_lote';

    protected $fillable = ['id_producto', 'numero_lote', 'fecha_caducidad'];

    public function producto()
    {
        return $this->belongsTo(ProductoInventario::class, 'id_producto', 'id_producto');
    }
}
