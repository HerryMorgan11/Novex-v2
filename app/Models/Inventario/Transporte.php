<?php

namespace App\Models\Inventario;

use App\Enums\Inventario\TransporteEstado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transporte extends Model
{
    protected $table = 'recepciones';

    protected $primaryKey = 'id_recepcion';

    protected $fillable = [
        'codigo_recepcion',
        'nombre_camion',
        'patente',
        'id_proveedor',
        'fecha_estimada',
        'fecha_recepcion',
        'estado',
        'observaciones',
        'creado_por',
        'fecha_creacion',
        'origen',
        'destino',
        'transportista',
        'payload_json',
        'origen_evento',
    ];

    protected $casts = [
        'estado' => TransporteEstado::class,
        'fecha_estimada' => 'datetime',
        'fecha_recepcion' => 'datetime',
        'fecha_creacion' => 'datetime',
        'payload_json' => 'array',
    ];

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }

    public function lineas(): HasMany
    {
        return $this->hasMany(LineaTransporte::class, 'id_recepcion', 'id_recepcion');
    }

    /** Genera el próximo código de recepción (TR-YYYY-NNNNN) */
    public static function generarCodigo(): string
    {
        $year = now()->year;
        $last = self::whereYear('created_at', $year)->count();

        return sprintf('TR-%d-%05d', $year, $last + 1);
    }
}
