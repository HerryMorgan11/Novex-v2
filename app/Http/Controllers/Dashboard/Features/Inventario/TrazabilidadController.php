<?php

namespace App\Http\Controllers\Dashboard\Features\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Inventario\Lote;
use App\Models\Inventario\TrazabilidadEvento;
use Illuminate\View\View;

class TrazabilidadController extends Controller
{
    public function historial(int $lote): View
    {
        $lote = Lote::whereKey($lote)->firstOrFail();

        $lote->load(['producto.categoria', 'ubicacion.estanteria.zona.almacen']);

        $eventos = TrazabilidadEvento::with(['recepcion', 'expedicion'])
            ->where('id_lote', $lote->id_lote)
            ->orderByDesc('fecha_evento')
            ->get();

        return view('dashboard.features.inventario.trazabilidad.historial', compact('lote', 'eventos'));
    }
}
