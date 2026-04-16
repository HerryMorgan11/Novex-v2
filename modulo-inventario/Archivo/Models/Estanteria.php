<?php

namespace Modules\ModuloInventario\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Estanteria extends Model
{
    use HasFactory;

    protected $table = 'estanterias';
    protected $primaryKey = 'id_estanteria';

    protected $fillable = ['id_almacen', 'id_zona', 'codigo'];

    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }

    public function zona()
    {
        return $this->belongsTo(Zona::class, 'id_zona', 'id_zona');
    }

    public function ubicaciones()
    {
        return $this->hasMany(Ubicacion::class, 'id_estanteria', 'id_estanteria');
    }
}
