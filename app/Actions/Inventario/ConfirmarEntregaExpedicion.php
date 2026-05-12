<?php

namespace App\Actions\Inventario;

use App\Enums\Inventario\ExpedicionEstado;
use App\Enums\Inventario\LoteEstado;
use App\Models\Inventario\Expedicion;
use App\Models\Inventario\TrazabilidadEvento;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

/**
 * Confirma la entrega de una expedición cuando llega la señal desde API externa.
 * Cierra el ciclo logístico: estado expedición → entregada, lotes → delivered.
 */
class ConfirmarEntregaExpedicion
{
    /**
     * Confirma la entrega de una expedición y cierra el ciclo logístico.
     *
     * Actualiza el estado de la expedición a 'entregada' y los lotes a 'delivered'.
     *
     * @param  array<string, mixed>  $datos
     *
     * @throws \RuntimeException Si la expedición no está en estado válido
     */
    public function ejecutar(
        Expedicion $expedicion,
        array $datos = [],
        string $origenEvento = 'api'
    ): Expedicion {
        return DB::transaction(function () use ($expedicion, $datos, $origenEvento) {
            throw_unless(
                in_array($expedicion->estado, [ExpedicionEstado::Expedida, ExpedicionEstado::EnTransito], true),
                \RuntimeException::class,
                "La expedición {$expedicion->referencia_expedicion} no está en estado válido para confirmar entrega."
            );

            // Cerrar expedición
            $expedicion->update([
                'estado' => ExpedicionEstado::Entregada,
                'fecha_confirmacion_entrega' => $datos['fecha_confirmacion'] ?? now(),
            ]);

            // Cerrar líneas y lotes
            foreach ($expedicion->lineas as $linea) {
                $linea->update(['estado' => 'entregada']);

                $lote = $linea->lote;
                if ($lote && $lote->estado === LoteEstado::Dispatched) {
                    $estadoAnterior = $lote->estado;
                    $lote->update(['estado' => LoteEstado::Delivered]);

                    TrazabilidadEvento::create([
                        'id_lote' => $lote->id_lote,
                        'id_producto' => $lote->id_producto,
                        'tipo_evento' => 'entregado',
                        'estado_anterior' => $estadoAnterior->value,
                        'estado_nuevo' => LoteEstado::Delivered->value,
                        'origen_evento' => $origenEvento,
                        'id_expedicion' => $expedicion->id_expedicion,
                        'payload' => ! empty($datos) ? $datos : null,
                        'observaciones' => $datos['observaciones'] ?? 'Entrega confirmada externamente.',
                        'fecha_evento' => $datos['fecha_confirmacion'] ?? now(),
                    ]);
                }
            }

            return $expedicion->fresh(['lineas.lote']);
        });
    }

    /**
     * Confirma la entrega mediante token de acceso público.
     *
     * @param  array<string, mixed>  $datos
     *
     * @throws ModelNotFoundException
     */
    public function ejecutarPorToken(string $token, array $datos = []): Expedicion
    {
        $expedicion = Expedicion::where('token_confirmacion', $token)->firstOrFail();

        return $this->ejecutar($expedicion, $datos, 'api');
    }
}
