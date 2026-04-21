<?php

namespace App\Http\Controllers\Dashboard\Features\Inventario;

use App\Enums\Inventario\LoteEstado;
use App\Http\Controllers\Controller;
use App\Models\Inventario\CategoriaProducto;
use App\Models\Inventario\Lote;
use App\Models\Inventario\Producto;
use App\Models\Inventario\UnidadMedida;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Consulta de stock general (lotes) y validación de productos "borrador".
 *
 * Los lotes entregados (Delivered) se ocultan de la vista por defecto al ser
 * histórico no operativo.
 */
class StockController extends Controller
{
    /** Listado de lotes en stock con filtros por estado, categoría y búsqueda libre. */
    public function index(Request $request): View
    {
        $query = Lote::with(['producto.categoria', 'producto.unidadMedida', 'ubicacion.estanteria.zona.almacen'])
            ->whereNotIn('estado', [LoteEstado::Delivered->value]);

        // Filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($inner) use ($q) {
                $inner->where('numero_lote', 'like', "%{$q}%")
                    ->orWhereHas('producto', fn ($p) => $p->where('nombre', 'like', "%{$q}%")
                        ->orWhere('sku', 'like', "%{$q}%"));
            });
        }
        if ($request->filled('categoria')) {
            $query->whereHas('producto', fn ($p) => $p->where('id_categoria', $request->categoria));
        }

        $lotes = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        $estados = LoteEstado::cases();
        $categorias = CategoriaProducto::orderBy('nombre')->get();

        return view('dashboard.features.inventario.stock.index', compact('lotes', 'estados', 'categorias'));
    }

    public function show(int $lote): View
    {
        $lote = Lote::whereKey($lote)->firstOrFail();

        $lote->load([
            'producto.categoria',
            'producto.unidadMedida',
            'ubicacion.estanteria.zona.almacen',
            'stock',
            'trazabilidad',
        ]);

        return view('dashboard.features.inventario.stock.show', compact('lote'));
    }

    /** Formulario para validar un producto borrador */
    public function validarProducto(int $producto): View
    {
        $producto = Producto::whereKey($producto)->firstOrFail();

        $categorias = CategoriaProducto::orderBy('nombre')->get();
        $unidades = UnidadMedida::orderBy('nombre')->get();

        return view('dashboard.features.inventario.stock.validar-producto', compact('producto', 'categorias', 'unidades'));
    }

    public function guardarValidacion(Request $request, int $producto): RedirectResponse
    {
        $producto = Producto::whereKey($producto)->firstOrFail();

        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
            'sku' => ['nullable', 'string', 'max:100'],
            'id_categoria' => ['nullable', 'integer', 'exists:categorias_producto,id_categoria'],
            'id_unidad_medida' => ['nullable', 'integer', 'exists:unidades_medida,id_unidad'],
            'descripcion' => ['nullable', 'string'],
            'estado_validacion' => ['required', 'in:activo,inactivo'],
        ]);

        $producto->update($data);

        return redirect()->route('inventario.stock.index')
            ->with('success', "Producto '{$producto->nombre}' validado correctamente.");
    }
}
