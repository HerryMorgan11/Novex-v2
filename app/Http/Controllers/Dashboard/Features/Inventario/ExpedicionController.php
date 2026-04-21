<?php

namespace App\Http\Controllers\Dashboard\Features\Inventario;

use App\Actions\Inventario\PrepararExpedicion;
use App\Enums\Inventario\LoteEstado;
use App\Http\Controllers\Controller;
use App\Models\Inventario\Expedicion;
use App\Models\Inventario\Lote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Gestión de expediciones (salidas de mercancía).
 *
 * La creación de una expedición delega en PrepararExpedicion (Action) para
 * mantener transaccionalidad: cambio de estado de lotes + registro de trazabilidad.
 */
class ExpedicionController extends Controller
{
    public function index(): View
    {
        $expediciones = Expedicion::with(['lineas.lote.producto'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('dashboard.features.inventario.expediciones.index', compact('expediciones'));
    }

    public function create(): View
    {
        $lotesDisponibles = Lote::with(['producto.unidadMedida', 'ubicacion'])
            ->where('estado', LoteEstado::Stored->value)
            ->orderByDesc('created_at')
            ->get();

        return view('dashboard.features.inventario.expediciones.create', compact('lotesDisponibles'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'destino' => ['required', 'string', 'max:255'],
            'vehiculo' => ['nullable', 'string', 'max:100'],
            'conductor' => ['nullable', 'string', 'max:150'],
            'fecha_salida' => ['nullable', 'date'],
            'observaciones' => ['nullable', 'string', 'max:500'],
            'tipo' => ['required', 'in:reparto,produccion'],
            'lineas' => ['required', 'array', 'min:1'],
            'lineas.*.id_lote' => ['required', 'integer', 'exists:lotes,id_lote'],
            'lineas.*.cantidad' => ['required', 'numeric', 'min:0.0001'],
            'lineas.*.unidad' => ['nullable', 'string', 'max:50'],
        ]);

        try {
            $expedicion = (new PrepararExpedicion)->ejecutar(
                $data,
                $data['lineas'],
                auth()->id()
            );
        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }

        return redirect()->route('inventario.expediciones.show', $expedicion->id_expedicion)
            ->with('success', "Expedición {$expedicion->referencia_expedicion} creada correctamente.");
    }

    public function show(int $expedicion): View
    {
        $expedicion = Expedicion::whereKey($expedicion)->firstOrFail();
        $expedicion->load(['lineas.lote.producto', 'lineas.lote.ubicacion']);

        return view('dashboard.features.inventario.expediciones.show', compact('expedicion'));
    }
}
