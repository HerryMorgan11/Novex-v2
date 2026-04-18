<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    protected $table = 'unidades_medida';

    protected $primaryKey = 'id_unidad';

    protected $fillable = ['nombre', 'abreviatura', 'factor_conversion'];
}
