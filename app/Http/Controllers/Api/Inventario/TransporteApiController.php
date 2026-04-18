<?php

namespace App\Http\Controllers\Api\Inventario;

use App\Actions\Inventario\RegistrarTransporteDesdeApi;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransporteApiController extends Controller
{
    /**
     * POST /api/inventario/transportes
     *
     * Recibe un transporte anunciado desde un sistema externo.
     * Crea el transporte, sus líneas y los lotes correspondientes.
     *
     * Body JSON:
     * {
     *   "referencia": "TR-EXT-001",
     *   "proveedor": "Empresa Logística S.A.",
     *   "origen": "Madrid",
     *   "destino": "Barcelona",
     *   "placa": "1234ABC",
     *   "transportista": "Juan García",
     *   "fecha_prevista": "2026-04-20T10:00:00",
     *   "observaciones": "Frágil",
     *   "lineas": [
     *     {
     *       "referencia_producto": "SKU-001",
     *       "nombre": "Tornillo M8 x 25",
     *       "cantidad": 1000,
     *       "unidad": "piezas"
     *     }
     *   ]
     * }
     */
    public function store(Request $request): JsonResponse
    {
        $payload = $request->validate([
            'referencia' => ['nullable', 'string', 'max:100'],
            'proveedor' => ['nullable', 'string', 'max:255'],
            'origen' => ['nullable', 'string', 'max:255'],
            'destino' => ['nullable', 'string', 'max:255'],
            'placa' => ['nullable', 'string', 'max:20'],
            'transportista' => ['nullable', 'string', 'max:150'],
            'fecha_prevista' => ['nullable', 'date'],
            'observaciones' => ['nullable', 'string'],
            'lineas' => ['required', 'array', 'min:1'],
            'lineas.*.referencia_producto' => ['nullable', 'string', 'max:100'],
            'lineas.*.nombre' => ['required', 'string', 'max:255'],
            'lineas.*.cantidad' => ['required', 'numeric', 'min:0.0001'],
            'lineas.*.unidad' => ['nullable', 'string', 'max:50'],
        ]);

        try {
            $transporte = (new RegistrarTransporteDesdeApi)->ejecutar(
                array_merge($payload, ['raw_payload' => $request->all()])
            );
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al procesar el transporte.',
                'detalle' => $e->getMessage(),
            ], 422);
        }

        $borradores = $transporte->lineas
            ->filter(fn ($l) => $l->producto?->esBorrador())
            ->map(fn ($l) => $l->producto_codigo_ref ?? $l->producto?->sku)
            ->filter()
            ->values()
            ->toArray();

        return response()->json([
            'success' => true,
            'transporte_id' => $transporte->id_recepcion,
            'codigo_recepcion' => $transporte->codigo_recepcion,
            'estado' => $transporte->estado,
            'total_lineas' => $transporte->lineas->count(),
            'productos_creados_borrador' => $borradores,
            'mensaje' => count($borradores)
                ? 'Transporte registrado. Algunos productos fueron creados como borrador y requieren validación manual.'
                : 'Transporte registrado correctamente.',
        ], 201);
    }
}
