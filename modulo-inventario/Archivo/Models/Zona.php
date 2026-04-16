<?php

namespace Modules\ModuloInventario\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Zona extends Model
{
    use HasFactory;

    protected $table = 'zonas';
    protected $primaryKey = 'id_zona';

    protected $fillable = ['id_almacen', 'nombre'];

    public function almacen()
    {
        return $this->belongsTo(Almacen::class, 'id_almacen', 'id_almacen');
    }

    public function estanterias()
    {
        return $this->hasMany(Estanteria::class, 'id_zona', 'id_zona');
    }
}
