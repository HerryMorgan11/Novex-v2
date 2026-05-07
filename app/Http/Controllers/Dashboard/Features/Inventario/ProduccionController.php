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
        $lote = Lote::with('producto')->whereKey($lote)->first();

        if (! $lote) {
            return back()->with('error', 'No se encontró el lote seleccionado.');
        }

        if (! $lote->producto) {
            return back()->with('error', "El lote {$lote->numero_lote} no tiene un producto asociado.");
        }

        $request->validate([
            'observaciones' => ['nullable', 'string', 'max:500'],
        ]);

        try {
            (new MoverAProduccion)->ejecutar($lote, auth()->id(), $request->observaciones);
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', 'No se pudo mover el lote a producción. Revisa los datos e inténtalo de nuevo.');
        }

        return back()->with('success', "Lote {$lote->numero_lote} movido a producción.");
    }
}
