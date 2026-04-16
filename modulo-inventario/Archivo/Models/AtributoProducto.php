<?php

namespace Modules\ModuloInventario\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AtributoProducto extends Model
{
    use HasFactory;

    protected $table = 'atributos_producto';
    protected $primaryKey = 'id_atributo';

    protected $fillable = [
        'nombre',
        'tipo_dato',
    ];
}
