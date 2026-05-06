<?php

namespace App\Http\Controllers\Api\Inventario;

use App\Actions\Inventario\ConfirmarEntregaExpedicion;
use App\Http\Controllers\Controller;
use App\Models\Inventario\Expedicion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Endpoint externo para cerrar el ciclo logístico al confirmar la entrega de una expedición.
 *
 * Protegido por AutenticarApiInventario (Bearer token) + InitializeTenancyFromApi (X-Tenant-Id).
 */
class ExpedicionApiController extends Controller
{
    /**
     * POST /api/inventario/expediciones/{referencia}/confirmar-entrega
     *
     * Confirma la entrega de una expedición desde un sistema externo.
     * Cierra el ciclo logístico del inventario.
     *
     * Body JSON:
     * {
     *   "fecha_confirmacion": "2026-04-20T14:30:00",
     *   "destinatario": "Almacén Cliente S.A.",
     *   "observaciones": "Entregado sin incidencias"
     * }
     */
    public function confirmarEntrega(Request $request, string $referencia): JsonResponse
    {
        $datos = $request->validate([
            'fecha_confirmacion' => ['nullable', 'date'],
            'destinatario' => ['nullable', 'string', 'max:255'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        $expedicion = Expedicion::where('referencia_expedicion', $referencia)->first();

        if (! $expedicion) {
            return response()->json([
                'error' => "No se encontró la expedición con referencia: {$referencia}",
            ], 404);
        }

        try {
            $expedicion = (new ConfirmarEntregaExpedicion)->ejecutar($expedicion, $datos, 'api');
        } catch (\RuntimeException $e) {
            return response()->json([
                'error' => 'No se pudo confirmar la entrega.',
                'detalle' => $e->getMessage(),
            ], 422);
        }

        return response()->json([
            'success' => true,
            'expedicion_id' => $expedicion->id_expedicion,
            'referencia_expedicion' => $expedicion->referencia_expedicion,
            'estado' => $expedicion->estado,
            'fecha_confirmacion_entrega' => $expedicion->fecha_confirmacion_entrega,
            'lineas_confirmadas' => $expedicion->lineas->count(),
            'mensaje' => 'Entrega confirmada. Ciclo logístico cerrado.',
        ]);
    }
}
