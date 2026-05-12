<?php

namespace App\Models\Inventario;

use App\Enums\Inventario\TransporteEstado;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa una recepción de mercancía (transporte de entrada).
 *
 * @property int $id_recepcion
 * @property string $codigo_recepcion
 * @property string|null $nombre_camion
 * @property string|null $patente
 * @property int|null $id_proveedor
 * @property Carbon|null $fecha_estimada
 * @property Carbon|null $fecha_recepcion
 * @property TransporteEstado $estado
 * @property string|null $observaciones
 * @property string|null $creado_por
 * @property Carbon|null $fecha_creacion
 * @property string|null $origen
 * @property string|null $destino
 * @property string|null $transportista
 * @property array|null $payload_json
 * @property string|null $origen_evento
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Proveedor|null $proveedor
 * @property-read Collection<int, LineaTransporte> $lineas
 */
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

    /**
     * Obtiene el proveedor asociado al transporte.
     */
    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }

    /**
     * Obtiene las líneas de productos del transporte.
     */
    public function lineas(): HasMany
    {
        return $this->hasMany(LineaTransporte::class, 'id_recepcion', 'id_recepcion');
    }

    /**
     * Genera el próximo código de recepción con formato TR-YYYY-NNNNN.
     */
    public static function generarCodigo(): string
    {
        $year = now()->year;
        $last = self::whereYear('created_at', $year)->count();

        return sprintf('TR-%d-%05d', $year, $last + 1);
    }
}
