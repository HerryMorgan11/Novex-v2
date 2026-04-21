<?php

namespace App\Http\Controllers\Dashboard\Features\Inventario;

use App\Actions\Inventario\MoverAProduccion;
use App\Enums\Inventario\LoteEstado;
use App\Http\Controllers\Controller;
use App\Models\Inventario\Lote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Mueve lotes entre los estados Stored ↔ InProduction.
 *
 * La transición delega en MoverAProduccion (Action) para mantener la lógica
 * de cambio de estado + trazabilidad en un único punto.
 */
class ProduccionController extends Controller
{
    public function index(): View
    {
        $lotesDisponibles = Lote::with(['producto.categoria', 'ubicacion'])
            ->where('estado', LoteEstado::Stored->value)
            ->orderByDesc('created_at')
            ->paginate(15);

        $lotesEnProduccion = Lote::with(['producto.categoria', 'trazabilidad'])
            ->where('estado', LoteEstado::InProduction->value)
            ->orderByDesc('updated_at')
            ->paginate(15);

        return view('dashboard.features.inventario.produccion.index', compact(
            'lotesDisponibles',
            'lotesEnProduccion'
        ));
    }

    public function mover(Request $request, int $lote): RedirectResponse
    {
        $lote = Lote::whereKey($lote)->firstOrFail();

        $request->validate([
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            (new MoverAProduccion)->ejecutar($lote, auth()->id(), $request->observaciones);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', "Lote {$lote->numero_lote} movido a producción.");
    }
}
