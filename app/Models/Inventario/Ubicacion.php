<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function estanteria(): BelongsTo
    {
        return $this->belongsTo(Estanteria::class, 'id_estanteria', 'id_estanteria');
    }

    public function lotes(): HasMany
    {
        return $this->hasMany(Lote::class, 'id_ubicacion', 'id_ubicacion');
    }

    /** Código legible: ALM-Z01-EST-A1 */
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
