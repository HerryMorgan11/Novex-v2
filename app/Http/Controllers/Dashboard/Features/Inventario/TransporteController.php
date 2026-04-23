<?php

namespace App\Http\Controllers\Dashboard\Features\Inventario;

use App\Actions\Inventario\RecibirLote;
use App\Http\Controllers\Controller;
use App\Models\Inventario\LineaTransporte;
use App\Models\Inventario\Transporte;
use App\Models\Inventario\Ubicacion;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Gestión de transportes de entrada (recepciones).
 *
 * Cada línea de transporte se recibe individualmente asignándole una ubicación.
 * La transición (creación de lote + trazabilidad) se delega en RecibirLote (Action).
 */
class TransporteController extends Controller
{
    public function index(): View
    {
        $transportes = Transporte::with(['lineas', 'proveedor'])
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('dashboard.features.inventario.transportes.index', compact('transportes'));
    }

    public function show(int $transporte): View
    {
        $transporte = Transporte::whereKey($transporte)->firstOrFail();
        $transporte->load(['lineas.producto', 'lineas.lote.ubicacion', 'proveedor']);

        $ubicaciones = Ubicacion::with(['estanteria.zona.almacen'])
            ->where(function ($consultaUbicaciones) {
                $consultaUbicaciones->where('activa', true)
                    ->orWhereNull('activa');
            })
            ->get()
            ->sortBy(function ($ubicacion) {
                return $ubicacion->codigoCompleto();
            })
            ->groupBy(function ($ubicacion) {
                return $ubicacion->estanteria?->almacen?->nombre ?? 'Sin almacén';
            })
            ->map(function ($ubicacionesPorAlmacen) {
                return $ubicacionesPorAlmacen->map(function ($ubicacion) {
                    return [
                        'id' => $ubicacion->id_ubicacion,
                        'codigo' => $ubicacion->codigoCompleto(),
                        'zona' => $ubicacion->estanteria?->zona?->nombre,
                        'estanteria' => $ubicacion->estanteria?->codigo,
                    ];
                });
            });

        return view('dashboard.features.inventario.transportes.show', compact('transporte', 'ubicaciones'));
    }

    public function recibirLinea(Request $request, int $transporte, int $lineaId): RedirectResponse
    {
        $transporte = Transporte::whereKey($transporte)->firstOrFail();

        $request->validate([
            'id_ubicacion' => ['required', 'integer', 'exists:ubicaciones,id_ubicacion'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        $lineaTransporte = LineaTransporte::where('id_recepcion', $transporte->id_recepcion)
            ->where('id', $lineaId)
            ->firstOrFail();

        if ($lineaTransporte->estado_linea === 'ubicada') {
            return back()->with('error', 'Esta línea ya ha sido recibida.');
        }

        $ubicacionDestino = Ubicacion::findOrFail($request->id_ubicacion);
        /** @var User|null $user */
        $usuarioAutenticado = $request->user();

        try {
            (new RecibirLote)->ejecutar(
                $lineaTransporte,
                $ubicacionDestino,
                $usuarioAutenticado?->getAuthIdentifier(),
                $request->observaciones
            );
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Línea recibida y ubicada correctamente en {$ubicacionDestino->codigoCompleto()}.");
    }
}
