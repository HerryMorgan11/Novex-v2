<?php

namespace App\Http\Controllers\Dashboard\Features\Inventario;

use App\Http\Controllers\Controller;
use App\Models\Inventario\Almacen;
use App\Models\Inventario\Estanteria;
use App\Models\Inventario\Ubicacion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Gestión de la estructura física del inventario: almacén → zona → estantería → ubicación.
 *
 * Todos los endpoints storeX operan sobre el mismo almacén de la ruta padre y
 * preservan la jerarquía (una estantería solo puede asociarse a zonas del mismo almacén).
 */
class AlmacenController extends Controller
{
    public function index(): View
    {
        $almacenes = Almacen::with(['zonas.estanterias.ubicaciones'])
            ->where('activo', true)
            ->get();

        return view('dashboard.features.inventario.almacenes.index', compact('almacenes'));
    }

    public function create(): View
    {
        return view('dashboard.features.inventario.almacenes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'direccion' => ['nullable', 'string'],
            'responsable' => ['nullable', 'string', 'max:150'],
        ]);

        Almacen::create($data);

        return redirect()->route('inventario.almacenes.index')
            ->with('success', "Almacén '{$data['nombre']}' creado correctamente.");
    }

    public function storeZona(Request $request, int $almacen): RedirectResponse
    {
        $almacen = Almacen::whereKey($almacen)->firstOrFail();

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
        ]);

        $almacen->zonas()->create($data);

        return back()->with('success', "Zona '{$data['nombre']}' añadida al almacén.");
    }

    public function storeEstanteria(Request $request, int $almacen): RedirectResponse
    {
        $almacen = Almacen::whereKey($almacen)->firstOrFail();

        $data = $request->validate([
            'id_zona' => ['required', 'integer', 'exists:zonas,id_zona,id_almacen,'.$almacen->id_almacen],
            'codigo' => ['required', 'string', 'max:50'],
        ]);

        Estanteria::create(['id_almacen' => $almacen->id_almacen] + $data);

        return back()->with('success', "Estantería '{$data['codigo']}' creada.");
    }

    public function storeUbicacion(Request $request, int $almacen): RedirectResponse
    {
        $almacen = Almacen::whereKey($almacen)->firstOrFail();

        $data = $request->validate([
            'id_estanteria' => ['required', 'integer', 'exists:estanterias,id_estanteria'],
            'pasillo' => ['nullable', 'string', 'max:20'],
            'nivel' => ['nullable', 'string', 'max:20'],
            'posicion' => ['nullable', 'string', 'max:20'],
            'capacidad' => ['nullable', 'integer', 'min:1'],
        ]);

        $estanteria = Estanteria::where('id_almacen', $almacen->id_almacen)
            ->whereKey($data['id_estanteria'])
            ->firstOrFail();

        $data['id_estanteria'] = $estanteria->id_estanteria;
        $ubicacion = Ubicacion::create($data);

        // Generar código de ubicación automático
        $ubicacion->load('estanteria.zona.almacen');
        $codigo = $ubicacion->codigoCompleto();
        $ubicacion->update(['codigo_ubicacion' => $codigo]);

        return back()->with('success', "Ubicación {$codigo} creada.");
    }
}
