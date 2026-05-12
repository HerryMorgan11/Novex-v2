<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa una unidad de medida para productos.
 *
 * @property int $id_unidad
 * @property string $nombre
 * @property string|null $abreviatura
 * @property float|null $factor_conversion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class UnidadMedida extends Model
{
    protected $table = 'unidades_medida';

    protected $primaryKey = 'id_unidad';

    protected $fillable = ['nombre', 'abreviatura', 'factor_conversion'];
}
