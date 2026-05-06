<?php

namespace App\Models\Inventario;

use App\Enums\Inventario\ExpedicionEstado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Expedicion extends Model
{
    protected $table = 'expediciones';

    protected $primaryKey = 'id_expedicion';

    protected $fillable = [
        'referencia_expedicion',
        'tipo',
        'destino',
        'vehiculo',
        'conductor',
        'fecha_salida',
        'fecha_confirmacion_entrega',
        'estado',
        'observaciones',
        'id_usuario',
        'token_confirmacion',
    ];

    protected $casts = [
        'estado' => ExpedicionEstado::class,
        'fecha_salida' => 'datetime',
        'fecha_confirmacion_entrega' => 'datetime',
    ];

    public function lineas(): HasMany
    {
        return $this->hasMany(LineaExpedicion::class, 'id_expedicion', 'id_expedicion');
    }

    /** Genera el próximo código de expedición (EXP-YYYY-NNNNN) */
    public static function generarReferencia(): string
    {
        $year = now()->year;
        $last = self::whereYear('created_at', $year)->count();

        return sprintf('EXP-%d-%05d', $year, $last + 1);
    }
}
