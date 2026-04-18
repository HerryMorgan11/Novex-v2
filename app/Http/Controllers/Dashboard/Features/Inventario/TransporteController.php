<?php

namespace App\Http\Controllers\Dashboard\Features\Inventario;

use App\Actions\Inventario\RecibirLote;
use App\Http\Controllers\Controller;
use App\Models\Inventario\LineaTransporte;
use App\Models\Inventario\Transporte;
use App\Models\Inventario\Ubicacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransporteController extends Controller
{
    public function index(): View
    {
        $transportes = Transporte::with(['lineas', 'proveedor'])
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('dashboard.features.inventario.transportes.index', compact('transportes'));
    }

    public function show(Transporte $transporte): View
    {
        $transporte->load(['lineas.producto', 'lineas.lote.ubicacion', 'proveedor']);

        $ubicaciones = Ubicacion::with(['estanteria.zona.almacen'])
            ->where('activa', true)
            ->get()
            ->map(fn ($u) => [
                'id' => $u->id_ubicacion,
                'codigo' => $u->codigoCompleto(),
            ]);

        return view('dashboard.features.inventario.transportes.show', compact('transporte', 'ubicaciones'));
    }

    public function recibirLinea(Request $request, Transporte $transporte, int $lineaId): RedirectResponse
    {
        $request->validate([
            'id_ubicacion' => ['required', 'integer', 'exists:ubicaciones,id_ubicacion'],
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        $linea = LineaTransporte::where('id_recepcion', $transporte->id_recepcion)
            ->where('id', $lineaId)
            ->firstOrFail();

        if ($linea->estado_linea === 'ubicada') {
            return back()->with('error', 'Esta línea ya ha sido recibida.');
        }

        $ubicacion = Ubicacion::findOrFail($request->id_ubicacion);

        try {
            (new RecibirLote)->ejecutar(
                $linea,
                $ubicacion,
                auth()->id(),
                $request->observaciones
            );
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Línea recibida y ubicada correctamente en {$ubicacion->codigoCompleto()}.");
    }
}
