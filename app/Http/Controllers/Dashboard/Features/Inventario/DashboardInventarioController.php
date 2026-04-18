<?php

namespace App\Http\Controllers\Dashboard\Features\Inventario;

use App\Enums\Inventario\LoteEstado;
use App\Enums\Inventario\TransporteEstado;
use App\Http\Controllers\Controller;
use App\Models\Inventario\Expedicion;
use App\Models\Inventario\Lote;
use App\Models\Inventario\Producto;
use App\Models\Inventario\Transporte;
use Illuminate\View\View;

class DashboardInventarioController extends Controller
{
    public function index(): View
    {
        $stats = [
            'productos_activos' => Producto::where('estado_validacion', 'activo')->count(),
            'productos_borrador' => Producto::where('estado_validacion', 'borrador')->count(),
            'lotes_almacenados' => Lote::where('estado', LoteEstado::Stored->value)->count(),
            'lotes_en_transito' => Lote::where('estado', LoteEstado::Dispatched->value)->count(),
            'transportes_pendientes' => Transporte::whereIn('estado', [
                TransporteEstado::Anunciado->value,
                TransporteEstado::PendienteUbicacion->value,
            ])->count(),
            'expediciones_activas' => Expedicion::whereIn('estado', ['preparada', 'expedida', 'en_transito'])->count(),
        ];

        $transportesRecientes = Transporte::with('lineas')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $expedicionesRecientes = Expedicion::with('lineas')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        $lotesBloqueados = Lote::with('producto')
            ->whereIn('estado', [LoteEstado::Blocked->value, LoteEstado::Incident->value])
            ->limit(5)
            ->get();

        return view('dashboard.features.inventario.index', compact(
            'stats',
            'transportesRecientes',
            'expedicionesRecientes',
            'lotesBloqueados'
        ));
    }
}
