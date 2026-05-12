<?php

namespace App\Models\Inventario;

use App\Enums\Inventario\LoteEstado;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa un lote de producto en el inventario.
 *
 * Un lote agrupa unidades de un producto con fecha de caducidad
 * y estado de trazabilidad común.
 *
 * @property int $id_lote
 * @property int $id_producto
 * @property string $numero_lote
 * @property Carbon|null $fecha_caducidad
 * @property LoteEstado $estado
 * @property int|null $id_ubicacion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Producto $producto
 * @property-read Ubicacion|null $ubicacion
 * @property-read Collection<int, Stock> $stock
 * @property-read Collection<int, TrazabilidadEvento> $trazabilidad
 * @property-read Collection<int, LineaExpedicion> $lineasExpedicion
 */
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

    /**
     * Obtiene el producto al que pertenece el lote.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }

    /**
     * Obtiene la ubicación actual del lote.
     */
    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'id_ubicacion', 'id_ubicacion');
    }

    /**
     * Obtiene los registros de stock asociados a este lote.
     */
    public function stock(): HasMany
    {
        return $this->hasMany(Stock::class, 'id_lote', 'id_lote');
    }

    /**
     * Obtiene los eventos de trazabilidad del lote, ordenados por fecha descendente.
     */
    public function trazabilidad(): HasMany
    {
        return $this->hasMany(TrazabilidadEvento::class, 'id_lote', 'id_lote')
            ->orderByDesc('fecha_evento');
    }

    /**
     * Obtiene las líneas de expedición que incluyen este lote.
     */
    public function lineasExpedicion(): HasMany
    {
        return $this->hasMany(LineaExpedicion::class, 'id_lote', 'id_lote');
    }

    /**
     * Calcula la cantidad física total del lote según stock.
     */
    public function cantidadFisica(): float
    {
        return (float) $this->stock()->sum('cantidad_actual');
    }

    /**
     * Calcula la cantidad disponible (física menos reservada).
     */
    public function cantidadDisponible(): float
    {
        return max(0, $this->cantidadFisica() - (float) $this->stock()->sum('cantidad_reservada'));
    }

    /**
     * Determina si el lote está disponible para operaciones.
     */
    public function estaDisponible(): bool
    {
        return in_array($this->estado, [LoteEstado::Stored], true)
            && $this->cantidadDisponible() > 0;
    }
}
