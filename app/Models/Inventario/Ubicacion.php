<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa una ubicación física dentro de una estantería.
 *
 * @property int $id_ubicacion
 * @property int $id_estanteria
 * @property string|null $pasillo
 * @property string|null $nivel
 * @property string|null $posicion
 * @property int|null $capacidad
 * @property string|null $codigo_ubicacion
 * @property bool $activa
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Estanteria $estanteria
 * @property-read Collection<int, Lote> $lotes
 */
class Ubicacion extends Model
{
    protected $table = 'ubicaciones';

    protected $primaryKey = 'id_ubicacion';

    protected $fillable = [
        'id_estanteria',
        'pasillo',
        'nivel',
        'posicion',
        'capacidad',
        'codigo_ubicacion',
        'activa',
    ];

    protected $casts = ['activa' => 'boolean'];

    /**
     * Obtiene la estantería a la que pertenece la ubicación.
     */
    public function estanteria(): BelongsTo
    {
        return $this->belongsTo(Estanteria::class, 'id_estanteria', 'id_estanteria');
    }

    /**
     * Obtiene los lotes almacenados en esta ubicación.
     */
    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class, 'id_ubicacion', 'id_ubicacion');
    }

    /**
     * Genera el código legible completo de la ubicación.
     *
     * Compone el código a partir de almacén, zona, estantería, pasillo, nivel y posición.
     */
    public function codigoCompleto(): string
    {
        if ($this->codigo_ubicacion) {
            return $this->codigo_ubicacion;
        }

        $parts = array_filter([
            optional($this->estanteria?->almacen)->nombre,
            optional($this->estanteria?->zona)->nombre,
            optional($this->estanteria)->codigo,
            $this->pasillo,
            $this->nivel,
            $this->posicion,
        ]);

        return implode('-', $parts) ?: 'UBI-'.$this->id_ubicacion;
    }
}
