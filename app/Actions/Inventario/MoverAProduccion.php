<?php

namespace App\Actions\Inventario;

use App\Enums\Inventario\LoteEstado;
use App\Enums\Inventario\MovimientoTipo;
use App\Models\Inventario\DetalleMovimiento;
use App\Models\Inventario\Lote;
use App\Models\Inventario\MovimientoInventario;
use App\Models\Inventario\Stock;
use App\Models\Inventario\TrazabilidadEvento;
use Illuminate\Support\Facades\DB;

/**
 * Mueve un lote almacenado a producción.
 * El lote sale completamente del stock disponible.
 */
class MoverAProduccion
{
    public function ejecutar(Lote $lote, int $usuarioId, ?string $observaciones = null): Lote
    {
        return DB::transaction(function () use ($lote, $usuarioId, $observaciones) {
            throw_unless(
                $lote->estado === LoteEstado::Stored,
                \RuntimeException::class,
                "Solo se pueden mover a producción lotes en estado 'almacenado'. Estado actual: {$lote->estado->value}."
            );

            $cantidad = $lote->cantidadFisica();

            // Reservar toda la cantidad (sale del disponible)
            Stock::where('id_lote', $lote->id_lote)
                ->update(['cantidad_reservada' => DB::raw('cantidad_actual')]);

            $estadoAnterior = $lote->estado;
            $lote->update(['estado' => LoteEstado::InProduction]);

            // Movimiento
            $movimiento = MovimientoInventario::create([
                'fecha' => now(),
                'tipo' => MovimientoTipo::Produccion,
                'referencia' => "PROD-{$lote->numero_lote}",
                'observacion' => $observaciones,
                'id_lote' => $lote->id_lote,
                'id_usuario' => $usuarioId,
                'usuario' => $usuarioId,
            ]);

            DetalleMovimiento::create([
                'id_movimiento' => $movimiento->id_movimiento,
                'id_producto' => $lote->id_producto,
                'id_lote' => $lote->id_lote,
                'id_ubicacion_origen' => $lote->id_ubicacion,
                'id_ubicacion_destino' => null,
                'cantidad' => $cantidad,
            ]);

            // Trazabilidad
            TrazabilidadEvento::create([
                'id_lote' => $lote->id_lote,
                'id_producto' => $lote->id_producto,
                'tipo_evento' => 'produccion',
                'estado_anterior' => $estadoAnterior->value,
                'estado_nuevo' => LoteEstado::InProduction->value,
                'origen_evento' => 'manual',
                'id_usuario' => $usuarioId,
                'observaciones' => $observaciones,
                'fecha_evento' => now(),
            ]);

            return $lote->fresh();
        });
    }
}
