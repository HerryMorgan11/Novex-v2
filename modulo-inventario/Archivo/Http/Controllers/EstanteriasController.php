<?php

namespace Modules\ModuloInventario\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\ModuloInventario\Models\Estanteria;

class EstanteriasController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_almacen' => 'required|exists:almacenes,id_almacen',
            'codigo' => 'required|string|max:255'
        ]);

        Estanteria::create($request->all());

        return redirect()->back()->with('active_tab', 'estanteria');
    }

    /**
     * Display the specified resource.
     */
    public function estanteriasPorAlmacen($id)
    {
        $estanterias = Estanteria::where('id_almacen', $id)->get();
        return response()->json($estanterias);
    }

    public function show($id)
    {
        $naves = Estanteria::pluck('nombre', 'id');
        $almacenSeleccionado = $id;

        return view('moduloinventario::producto.InventarioProducto', compact('naves', 'almacenSeleccionado'));
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
