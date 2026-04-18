<?php

namespace App\Actions\Inventario;

use App\Enums\Inventario\ExpedicionEstado;
use App\Enums\Inventario\LoteEstado;
use App\Enums\Inventario\MovimientoTipo;
use App\Models\Inventario\DetalleMovimiento;
use App\Models\Inventario\Expedicion;
use App\Models\Inventario\LineaExpedicion;
use App\Models\Inventario\Lote;
use App\Models\Inventario\MovimientoInventario;
use App\Models\Inventario\Stock;
use App\Models\Inventario\TrazabilidadEvento;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Crea una expedición de reparto.
 * Descuenta inmediatamente el stock disponible. El ciclo se cierra
 * cuando llega la confirmación externa (ConfirmarEntregaExpedicion).
 *
 * $lineas = [
 *   ['id_lote' => 1, 'cantidad' => 100, 'unidad' => 'piezas'],
 *   ...
 * ]
 */
class PrepararExpedicion
{
    public function ejecutar(array $datos, array $lineas, int $usuarioId): Expedicion
    {
        return DB::transaction(function () use ($datos, $lineas, $usuarioId) {
            // Validar disponibilidad de todos los lotes antes de crear
            foreach ($lineas as $linea) {
                $lote = Lote::findOrFail($linea['id_lote']);

                throw_unless(
                    $lote->estado === LoteEstado::Stored,
                    \RuntimeException::class,
                    "El lote {$lote->numero_lote} no está disponible para reparto (estado: {$lote->estado->value})."
                );

                $disponible = $lote->cantidadDisponible();
                throw_unless(
                    $disponible >= (float) $linea['cantidad'],
                    \RuntimeException::class,
                    "Stock insuficiente para el lote {$lote->numero_lote}. Disponible: {$disponible}, solicitado: {$linea['cantidad']}."
                );
            }

            // Crear expedición
            $expedicion = Expedicion::create([
                'referencia_expedicion' => Expedicion::generarReferencia(),
                'tipo' => $datos['tipo'] ?? 'reparto',
                'destino' => $datos['destino'] ?? null,
                'vehiculo' => $datos['vehiculo'] ?? null,
                'conductor' => $datos['conductor'] ?? null,
                'fecha_salida' => $datos['fecha_salida'] ?? now(),
                'estado' => ExpedicionEstado::Expedida,
                'observaciones' => $datos['observaciones'] ?? null,
                'id_usuario' => $usuarioId,
                'token_confirmacion' => Str::random(32),
            ]);

            foreach ($lineas as $lineaDatos) {
                $lote = Lote::find($lineaDatos['id_lote']);
                $cantidad = (float) $lineaDatos['cantidad'];

                LineaExpedicion::create([
                    'id_expedicion' => $expedicion->id_expedicion,
                    'id_lote' => $lote->id_lote,
                    'id_producto' => $lote->id_producto,
                    'cantidad' => $cantidad,
                    'unidad' => $lineaDatos['unidad'] ?? null,
                    'estado' => 'expedida',
                ]);

                // Descuento inmediato: incrementar cantidad_reservada = sale del disponible
                Stock::where('id_lote', $lote->id_lote)
                    ->increment('cantidad_reservada', $cantidad);

                $estadoAnterior = $lote->estado;
                $lote->update(['estado' => LoteEstado::Dispatched]);

                // Movimiento de inventario
                $movimiento = MovimientoInventario::create([
                    'fecha' => now(),
                    'tipo' => MovimientoTipo::Reparto,
                    'referencia' => $expedicion->referencia_expedicion,
                    'observacion' => "Expedido a: {$expedicion->destino}",
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
                    'tipo_evento' => 'expedido',
                    'estado_anterior' => $estadoAnterior->value,
                    'estado_nuevo' => LoteEstado::Dispatched->value,
                    'origen_evento' => 'manual',
                    'id_usuario' => $usuarioId,
                    'id_expedicion' => $expedicion->id_expedicion,
                    'observaciones' => "Expedición {$expedicion->referencia_expedicion} creada. Destino: {$expedicion->destino}",
                    'fecha_evento' => now(),
                ]);
            }

            return $expedicion->load('lineas.lote.producto');
        });
    }
}
