<?php

namespace Modules\ModuloInventario\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Modules\ModuloInventario\Models\ProductoInventario;
use Modules\ModuloInventario\Models\CategoriaProducto;
use Modules\ModuloInventario\Models\Almacen;

class ModuloInventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = ProductoInventario::all();
        $categorias = CategoriaProducto::all();
        $naves = Almacen::all();
        
        return view('dashboard.features.inventory.index', compact('productos', 'categorias', 'naves'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = CategoriaProducto::all();
        $naves = Almacen::all();
        
        return view('dashboard.features.inventory.productos.create', compact('categorias', 'naves'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias_producto,id_categoria',
            'precio_venta' => 'required|numeric',
            'stock' => 'required|integer',
        ]);

        ProductoInventario::create([
            'nombre' => $validated['nombre'],
            'sku' => $validated['codigo'],
            'id_categoria' => $validated['categoria_id'],
            'precio_referencia' => $validated['precio_venta'],
            'descripcion' => $request->descripcion,
            'estado' => 'ACTIVO',
        ]);

        return redirect()->route('inventario.index')->with('success', 'Producto creado exitosamente');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('moduloinventario::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('moduloinventario::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
