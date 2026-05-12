<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa un evento de trazabilidad de un lote.
 *
 * Registra cada cambio de estado o movimiento significativo
 * de un lote a lo largo de la cadena logística.
 *
 * @property int $id
 * @property int $id_lote
 * @property int|null $id_producto
 * @property string $tipo_evento
 * @property string|null $estado_anterior
 * @property string|null $estado_nuevo
 * @property string|null $origen_evento
 * @property int|null $id_usuario
 * @property int|null $id_recepcion
 * @property int|null $id_expedicion
 * @property array|null $payload
 * @property string|null $observaciones
 * @property Carbon|null $fecha_evento
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Lote $lote
 * @property-read Producto|null $producto
 * @property-read Transporte|null $recepcion
 * @property-read Expedicion|null $expedicion
 */
class TrazabilidadEvento extends Model
{
    protected $table = 'trazabilidad_eventos';

    protected $fillable = [
        'id_lote',
        'id_producto',
        'tipo_evento',
        'estado_anterior',
        'estado_nuevo',
        'origen_evento',
        'id_usuario',
        'id_recepcion',
        'id_expedicion',
        'payload',
        'observaciones',
        'fecha_evento',
    ];

    protected $casts = [
        'payload' => 'array',
        'fecha_evento' => 'datetime',
    ];

    /**
     * Obtiene el lote asociado al evento.
     */
    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }

    /**
     * Obtiene el producto asociado al evento.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    /**
     * Obtiene la recepción (transporte) asociada al evento.
     */
    public function recepcion(): BelongsTo
    {
        return $this->belongsTo(Transporte::class, 'id_recepcion', 'id_recepcion');
    }

    /**
     * Obtiene la expedición asociada al evento.
     */
    public function expedicion(): BelongsTo
    {
        return $this->belongsTo(Expedicion::class, 'id_expedicion', 'id_expedicion');
    }

    /**
     * Devuelve el icono Iconify correspondiente al tipo de evento.
     */
    public function iconoEvento(): string
    {
        return match ($this->tipo_evento) {
            'recepcion' => 'lucide:truck',
            'ubicacion' => 'lucide:map-pin',
            'traslado' => 'lucide:arrow-right-left',
            'produccion' => 'lucide:factory',
            'preparado_reparto' => 'lucide:package-check',
            'expedido' => 'lucide:send',
            'entregado' => 'lucide:check-circle',
            'ajuste' => 'lucide:sliders-horizontal',
            'incidencia' => 'lucide:alert-triangle',
            'bloqueado' => 'lucide:lock',
            default => 'lucide:circle',
        };
    }
}
