<?php

namespace App\Models\Inventario;

use App\Enums\Inventario\MovimientoTipo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function lote(): BelongsTo
    {
        return $this->belongsTo(Lote::class, 'id_lote', 'id_lote');
    }

    public function detalles()
    {
        return $this->hasMany(DetalleMovimiento::class, 'id_movimiento', 'id_movimiento');
    }
}
