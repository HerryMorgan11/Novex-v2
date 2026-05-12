<?php

namespace App\Models\Inventario;

use App\Enums\Inventario\ExpedicionEstado;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa una expedición de mercancía (salida del almacén).
 *
 * @property int $id_expedicion
 * @property string $referencia_expedicion
 * @property string|null $tipo
 * @property string|null $destino
 * @property string|null $vehiculo
 * @property string|null $conductor
 * @property Carbon|null $fecha_salida
 * @property Carbon|null $fecha_confirmacion_entrega
 * @property ExpedicionEstado $estado
 * @property string|null $observaciones
 * @property int|null $id_usuario
 * @property string|null $token_confirmacion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, LineaExpedicion> $lineas
 */
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

    /**
     * Obtiene las líneas de la expedición.
     */
    public function lineas(): HasMany
    {
        return $this->hasMany(LineaExpedicion::class, 'id_expedicion', 'id_expedicion');
    }

    /**
     * Genera la próxima referencia de expedición con formato EXP-YYYY-NNNNN.
     */
    public static function generarReferencia(): string
    {
        $year = now()->year;
        $last = self::whereYear('created_at', $year)->count();

        return sprintf('EXP-%d-%05d', $year, $last + 1);
    }
}
