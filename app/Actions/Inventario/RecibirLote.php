<?php

namespace App\Actions\Inventario;

use App\Enums\Inventario\LoteEstado;
use App\Enums\Inventario\MovimientoTipo;
use App\Enums\Inventario\TransporteEstado;
use App\Models\Inventario\DetalleMovimiento;
use App\Models\Inventario\LineaTransporte;
use App\Models\Inventario\Lote;
use App\Models\Inventario\Movimiento;
use App\Models\Inventario\Stock;
use App\Models\Inventario\Transporte;
use App\Models\Inventario\TrazabilidadEvento;
use App\Models\Inventario\Ubicacion;
use Illuminate\Support\Facades\DB;

/**
 * Confirma la recepción física de un lote y lo asigna a una ubicación.
 * Crea el registro de stock, actualiza el estado del lote y cierra
 * la línea del transporte.
 *
 * Regla: recepción es todo o nada — cantidad_recibida == cantidad_esperada.
 */
class RecibirLote
{
    public function ejecutar(
        LineaTransporte $linea,
        Ubicacion $ubicacion,
        int|string|null $usuarioId,
        ?string $observaciones = null
    ): Lote {
        return DB::transaction(function () use ($linea, $ubicacion, $usuarioId, $observaciones) {
            $usuarioIdNumerico = is_numeric($usuarioId) ? (int) $usuarioId : null;
            $usuarioReferencia = $usuarioId !== null ? (string) $usuarioId : null;
            $lote = $linea->lote;

            // Validar que el lote está en estado correcto para ser recibido
            throw_unless(
                $lote->estado === LoteEstado::PendingInbound,
                \RuntimeException::class,
                "El lote {$lote->numero_lote} no está pendiente de recepción (estado: {$lote->estado->value})."
            );

            // Validar que la cantidad es correcta (todo o nada)
            $cantidadEsperada = (float) $linea->cantidad_esperada;

            // Crear o actualizar stock
            $stockExistente = Stock::where('id_producto', $linea->id_producto)
                ->where('id_ubicacion', $ubicacion->id_ubicacion)
                ->where('id_lote', $lote->id_lote)
                ->first();

            if ($stockExistente) {
                $stockExistente->increment('cantidad_actual', $cantidadEsperada);
            } else {
                Stock::create([
                    'id_producto' => $linea->id_producto,
                    'id_ubicacion' => $ubicacion->id_ubicacion,
                    'id_lote' => $lote->id_lote,
                    'cantidad_actual' => $cantidadEsperada,
                    'cantidad_reservada' => 0,
                ]);
            }

            // Actualizar línea del transporte
            $linea->update([
                'cantidad_recibida' => $cantidadEsperada,
                'estado_linea' => 'ubicada',
            ]);

            // Actualizar lote
            $estadoAnterior = $lote->estado;
            $lote->update([
                'estado' => LoteEstado::Stored,
                'id_ubicacion' => $ubicacion->id_ubicacion,
            ]);

            // Registrar movimiento de inventario
            $movimiento = Movimiento::create([
                'fecha' => now(),
                'tipo' => MovimientoTipo::Recepcion,
                'referencia' => $linea->transporte->codigo_recepcion ?? null,
                'observacion' => $observaciones,
                'id_lote' => $lote->id_lote,
                'id_usuario' => $usuarioIdNumerico,
                'usuario' => $usuarioReferencia,
            ]);

            DetalleMovimiento::create([
                'id_movimiento' => $movimiento->id_movimiento,
                'id_producto' => $linea->id_producto,
                'id_lote' => $lote->id_lote,
                'id_ubicacion_origen' => null,
                'id_ubicacion_destino' => $ubicacion->id_ubicacion,
                'cantidad' => $cantidadEsperada,
            ]);

            // Registrar trazabilidad
            TrazabilidadEvento::create([
                'id_lote' => $lote->id_lote,
                'id_producto' => $linea->id_producto,
                'tipo_evento' => 'ubicacion',
                'estado_anterior' => $estadoAnterior->value,
                'estado_nuevo' => LoteEstado::Stored->value,
                'origen_evento' => 'manual',
                'id_usuario' => $usuarioIdNumerico,
                'id_recepcion' => $linea->id_recepcion,
                'observaciones' => "Recibido y ubicado en: {$ubicacion->codigoCompleto()}. {$observaciones}",
                'fecha_evento' => now(),
            ]);

            // Actualizar estado del transporte si todas las líneas están ubicadas
            $transporte = $linea->transporte;
            $lineasPendientes = $transporte->lineas()
                ->whereNotIn('estado_linea', ['ubicada', 'incidencia'])
                ->count();

            $nuevoEstadoTransporte = $lineasPendientes === 0
                ? TransporteEstado::Completado
                : TransporteEstado::PendienteUbicacion;

            $transporte->update(['estado' => $nuevoEstadoTransporte]);

            return $lote->fresh();
        });
    }
}
