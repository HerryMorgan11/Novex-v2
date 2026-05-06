<?php

namespace App\Models\Inventario;

use App\Enums\Inventario\LoteEstado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lote extends Model
{
    protected $table = 'lotes';

    protected $primaryKey = 'id_lote';

    protected $fillable = [
        'id_producto',
        'numero_lote',
        'fecha_caducidad',
        'estado',
        'id_ubicacion',
    ];

    protected $casts = [
        'estado' => LoteEstado::class,
        'fecha_caducidad' => 'date',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'id_ubicacion', 'id_ubicacion');
    }

    public function stock(): HasMany
    {
        return $this->hasMany(Stock::class, 'id_lote', 'id_lote');
    }

    public function trazabilidad(): HasMany
    {
        return $this->hasMany(TrazabilidadEvento::class, 'id_lote', 'id_lote')
            ->orderByDesc('fecha_evento');
    }

    public function lineasExpedicion(): HasMany
    {
        return $this->hasMany(LineaExpedicion::class, 'id_lote', 'id_lote');
    }

    /** Cantidad física total según stock */
    public function cantidadFisica(): float
    {
        return (float) $this->stock()->sum('cantidad_actual');
    }

    /** Cantidad disponible (física - reservada) */
    public function cantidadDisponible(): float
    {
        return max(0, $this->cantidadFisica() - (float) $this->stock()->sum('cantidad_reservada'));
    }

    public function estaDisponible(): bool
    {
        return in_array($this->estado, [LoteEstado::Stored], true)
            && $this->cantidadDisponible() > 0;
    }
}
