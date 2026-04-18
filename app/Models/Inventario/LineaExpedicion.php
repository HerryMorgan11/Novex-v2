<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LineaExpedicion extends Model
{
    protected $table = 'lineas_expedicion';

    protected $fillable = [
        'id_expedicion',
        'id_lote',
        'id_producto',
        'cantidad',
        'unidad',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'cantidad' => 'decimal:4',
    ];

    public function expedicion(): BelongsTo
    {
        return $this->belongsTo(Expedicion::class, 'id_expedicion', 'id_expedicion');
    }

    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
