<?php

namespace Modules\ModuloInventario\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventarioFisico extends Model
{
    use HasFactory;

    protected $table = 'inventarios_fisicos';
    protected $primaryKey = 'id_inventario';

    protected $fillable = ['fecha', 'id_almacen', 'estado'];

    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleInventarioFisico::class, 'id_inventario', 'id_inventario');
    }
}
