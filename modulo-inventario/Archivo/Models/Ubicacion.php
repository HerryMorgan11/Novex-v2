<?php

namespace Modules\ModuloInventario\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ubicacion extends Model
{
    use HasFactory;

    protected $table = 'ubicaciones';
    protected $primaryKey = 'id_ubicacion';

    protected $fillable = ['id_estanteria', 'pasillo', 'nivel', 'posicion', 'capacidad'];

    public function estanteria()
    {
        return $this->belongsTo(Estanteria::class, 'id_estanteria', 'id_estanteria');
    }
}
