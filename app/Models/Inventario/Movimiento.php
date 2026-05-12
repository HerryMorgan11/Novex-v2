<?php

namespace App\Models\Inventario;

use App\Enums\Inventario\MovimientoTipo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa un movimiento de inventario (entrada, salida, ajuste, etc.).
 *
 * @property int $id_movimiento
 * @property Carbon $fecha
 * @property MovimientoTipo $tipo
 * @property string|null $referencia
 * @property string|null $observacion
 * @property string|null $usuario
 * @property int|null $id_lote
 * @property int|null $id_usuario
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Lote|null $lote
 * @property-read Collection<int, DetalleMovimiento> $detalles
 */
class Movimiento extends Model
{
    protected $table = 'movimientos_inventario';

    protected $primaryKey = 'id_movimiento';

    protected $fillable = [
        'fecha',
        'tipo',
        'referencia',
        'observacion',
        'usuario',
        'id_lote',
        'id_usuario',
    ];

    protected $casts = [
        'tipo' => MovimientoTipo::class,
        'fecha' => 'datetime',
    ];

    /**
     * Obtiene el lote asociado al movimiento.
     */
    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }

    /**
     * Obtiene las líneas de detalle del movimiento.
     *
     * @return HasMany
     */
    public function detalles()
    {
        return $this->hasMany(DetalleMovimiento::class, 'id_movimiento', 'id_movimiento');
    }
}
