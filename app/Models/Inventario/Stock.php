<?php

namespace App\Models\Inventario;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stock extends Model
{
    protected $table = 'stock';

    // Composite primary key — set manually
    public $incrementing = false;

    protected $primaryKey = null;

    protected $fillable = [
        'id_producto',
        'id_ubicacion',
        'id_lote',
        'cantidad_actual',
        'cantidad_reservada',
        'stock_minimo',
        'stock_maximo',
    ];

    protected $casts = [
        'cantidad_actual' => 'decimal:4',
        'cantidad_reservada' => 'decimal:4',
        'stock_minimo' => 'decimal:4',
        'stock_maximo' => 'decimal:4',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'id_ubicacion', 'id_ubicacion');
    }

    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }

    public function getDisponibleAttribute(): float
    {
        return max(0, (float) $this->cantidad_actual - (float) $this->cantidad_reservada);
    }
}
