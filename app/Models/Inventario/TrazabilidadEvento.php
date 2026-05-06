<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function recepcion(): BelongsTo
    {
        return $this->belongsTo(Transporte::class, 'id_recepcion', 'id_recepcion');
    }

    public function expedicion(): BelongsTo
    {
        return $this->belongsTo(Expedicion::class, 'id_expedicion', 'id_expedicion');
    }

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
