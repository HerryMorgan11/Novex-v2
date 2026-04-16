<?php

namespace Modules\ModuloInventario\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UnidadMedida extends Model
{
    use HasFactory;

    protected $table = 'unidades_medida';
    protected $primaryKey = 'id_unidad';

    protected $fillable = [
        'nombre',
        'abreviatura',
        'factor_conversion',
    ];
}
