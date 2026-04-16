<?php

namespace Modules\ModuloInventario\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Modules\ModuloInventario\Models\CategoriaProducto;

class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.features.inventory.categorias.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        CategoriaProducto::create([
            'nombre' => $request->nombre,
        ]);

        return redirect()->route('inventario.index')->with('success', 'Categoría creada con éxito');
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
