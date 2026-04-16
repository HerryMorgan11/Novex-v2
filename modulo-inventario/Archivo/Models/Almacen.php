<?php

namespace Modules\ModuloInventario\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Almacen extends Model
{
    use HasFactory;

    protected $table = 'almacenes';
    protected $primaryKey = 'id_almacen';

    protected $fillable = ['nombre', 'direccion', 'responsable'];

    public function zonas()
    {
        return $this->hasMany(Zona::class, 'id_almacen', 'id_almacen');
    }
}
