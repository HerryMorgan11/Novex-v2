<?php

namespace Modules\ModuloInventario\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MovimientoInventario extends Model
{
    use HasFactory;

    protected $table = 'movimientos_inventario';
    protected $primaryKey = 'id_movimiento';

    protected $fillable = ['fecha', 'tipo', 'referencia', 'observacion', 'usuario'];

    public function detalles()
    {
        return $this->hasMany(DetalleMovimientoInventario::class, 'id_movimiento', 'id_movimiento');
    }
}
