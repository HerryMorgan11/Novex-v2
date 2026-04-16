<?php

namespace Modules\ModuloInventario\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ModuloInventario\Models\Almacen;

class NaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $naves = Almacen::all();
        return view('moduloinventario::producto.InventarioProducto', compact('naves'));
    }

    public function mostrarNombreNavesCategorias()
    {
        $naves = Almacen::pluck('nombre', 'id');
        return view('moduloinventario::producto.InventarioProducto', compact('naves'));
    }

    public function mostrarNavesProducto()
    {
        $naves = Almacen::pluck('nombre', 'id');
        return view('moduloinventario::producto.ui.AñadirProducto', compact('naves'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.features.inventory.almacenes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'nullable|string|max:255',
            'responsable' => 'nullable|string|max:255'
        ]);

        Almacen::create($request->all());

        return redirect()->route('inventario.index')->with('success', 'Almacén creado con éxito');
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        //
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
