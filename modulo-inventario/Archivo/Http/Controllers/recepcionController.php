<?php

namespace Modules\ModuloInventario\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Modules\ModuloInventario\Models\Recepcion;
use Modules\ModuloInventario\Models\RecepcionProducto;
use Modules\ModuloInventario\Models\ProveedorInventario;
use Illuminate\Support\Facades\DB;

class recepcionController extends Controller
{

    /**
     * Leer y procesar la recepción desde JSON
     */
    public function obtenerRecepcion()
    {
        $filePath = 'private/endpoint-recepciones/recepcion01.json';

        try {
            if (!Storage::disk('local')->exists($filePath)) {
                // Intentar sin private por si las moscas o configuración de disco
                $filePath = 'endpoint-recepciones/recepcion01.json';
                if (!Storage::disk('local')->exists($filePath)) {
                    throw new \Exception("Archivo JSON no encontrado");
                }
            }

            // Leer el archivo JSON
            $jsonContent = Storage::disk('local')->get($filePath);

            // Decodificar el JSON a un array PHP
            $data = json_decode($jsonContent, true, 512, JSON_THROW_ON_ERROR);

            // Aquí puedes procesar/tratar los datos si es necesario
            // Por ejemplo: formatear fechas, calcular totales, etc.
            $data['total_productos'] = count($data['productos'] ?? []);
            $data['cantidad_total'] = collect($data['productos'] ?? [])->sum('cantidad');

            return $data;
        } catch (\Exception $e) {
            Log::error('Error al cargar recepción JSON', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recepciones = Recepcion::with(['proveedor', 'productos.producto'])
            ->orderByDesc('id_recepcion')
            ->get();

        // Si no hay datos en la BD, intentamos cargar desde JSON una vez
        if ($recepciones->isEmpty()) {
            $jsonData = $this->obtenerRecepcion();
            if ($jsonData) {
                try {
                    $this->guardarRecepcion($jsonData);
                    $recepciones = Recepcion::with(['proveedor', 'productos.producto'])
                        ->orderByDesc('id_recepcion')
                        ->get();
                } catch (\Exception $e) {
                    Log::error('Error al guardar recepción desde JSON: ' . $e->getMessage());
                }
            }
        }

        $dataList = $recepciones->map(fn($r) => $this->mapearRecepcion($r));

        return view('moduloinventario::recepciones.mainRecepciones', [
            'recepciones' => $dataList,
            'error' => $recepciones->isEmpty() ? 'No hay recepciones registradas.' : null
        ]);
    }

    /**
     * Retornar recepción como JSON (para peticiones AJAX)
     */
    public function recepcionJSON()
    {
        $recepcion = Recepcion::with(['proveedor', 'productos.producto'])
            ->orderByDesc('id_recepcion')
            ->first();

        if (!$recepcion) {
            $data = $this->obtenerRecepcion();
            if ($data) {
                $this->guardarRecepcion($data);
                $recepcion = Recepcion::with(['proveedor', 'productos.producto'])
                    ->orderByDesc('id_recepcion')
                    ->first();
            }
        }

        if (!$recepcion) {
            return response()->json(['error' => 'No hay recepciones registradas'], 404);
        }

        return response()->json($this->mapearRecepcion($recepcion));
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
        $validated = $request->validate([
            'codigo_recepcion' => 'required|string|max:255|unique:recepciones,codigo_recepcion',
            'nombre_camion' => 'nullable|string|max:255',
            'patente' => 'nullable|string|max:20',
            'id_proveedor' => 'nullable|integer|exists:proveedores,id_proveedor',
            'fecha_estimada' => 'nullable|date',
            'fecha_recepcion' => 'nullable|date',
            'estado' => 'nullable|string|in:PENDIENTE,EN_CURSO,COMPLETADA,CANCELADA',
            'observaciones' => 'nullable|string',
            'creado_por' => 'nullable|string|max:255',
            'fecha_creacion' => 'nullable|date',
        ]);

        Recepcion::create($validated);
    }

    private function guardarRecepcion(array $data): void
    {
        // Mapear campos del JSON a los de la BD
        $mapeo = [
            'codigo_recepcion' => $data['recepcion_id'] ?? null,
            'nombre_camion' => $data['nombre_camion'] ?? null,
            'patente' => $data['patente'] ?? null,
            'fecha_estimada' => $data['fecha_estimada'] ? date('Y-m-d H:i:s', strtotime($data['fecha_estimada'])) : null,
            'estado' => $data['estado'] ?? 'PENDIENTE',
            'observaciones' => $data['observaciones'] ?? null,
            'creado_por' => $data['creado_por'] ?? 'sistema',
            'fecha_creacion' => $data['fecha_creacion'] ? date('Y-m-d H:i:s', strtotime($data['fecha_creacion'])) : now(),
        ];

        // Buscar proveedor si existe por nombre o ID externo si tuviéramos tabla de mapeo
        // Por ahora lo dejamos nulo o buscamos por nombre si quieres aproximación
        $proveedor = null;
        if (isset($data['proveedor']['nombre'])) {
            $proveedor = ProveedorInventario::where('nombre_empresa', $data['proveedor']['nombre'])
                ->orWhere('nombre', $data['proveedor']['nombre'])
                ->first();
        }
        $mapeo['id_proveedor'] = $proveedor?->id_proveedor;

        DB::transaction(function () use ($mapeo, $data) {
            // Evitar duplicados por código
            $recepcion = Recepcion::updateOrCreate(
                ['codigo_recepcion' => $mapeo['codigo_recepcion']],
                $mapeo
            );

            // Guardar productos
            if (isset($data['productos']) && is_array($data['productos'])) {
                // Limpiar productos anteriores si es un update (opcional según lógica de negocio)
                $recepcion->productos()->delete();

                foreach ($data['productos'] as $prod) {
                    RecepcionProducto::create([
                        'id_recepcion' => $recepcion->id_recepcion,
                        'producto_codigo_ref' => $prod['producto_id'] ?? null,
                        'producto_nombre_ref' => $prod['nombre'] ?? null,
                        'cantidad_esperada' => $prod['cantidad'] ?? 0,
                        'unidad' => $prod['unidad'] ?? null,
                    ]);
                }
            }
        });
    }

    private function mapearRecepcion(Recepcion $recepcion): array
    {
        $proveedor = $recepcion->proveedor;
        $productos = $recepcion->productos ?? collect();

        $productosFormateados = $productos->map(function ($producto) {
            return [
                'producto_id' => $producto->producto_codigo_ref ?? 'N/A',
                'nombre' => $producto->producto_nombre_ref ?? data_get($producto, 'producto.nombre', 'N/A'),
                'cantidad' => (float)$producto->cantidad_esperada,
                'unidad' => $producto->unidad ?? 'N/A',
            ];
        })->all();

        return [
            'recepcion_db_id' => $recepcion->id_recepcion,
            'recepcion_id' => $recepcion->codigo_recepcion, // Usamos el código para la UI
            'nombre_camion' => $recepcion->nombre_camion ?? 'No especificado',
            'patente' => $recepcion->patente ?? 'N/A',
            'fecha_estimada' => $recepcion->fecha_estimada ? $recepcion->fecha_estimada->toIso8601String() : null,
            'fecha_recepcion' => $recepcion->fecha_recepcion ? $recepcion->fecha_recepcion->toIso8601String() : null,
            'estado' => $recepcion->estado,
            'observaciones' => $recepcion->observaciones,
            'creado_por' => $recepcion->creado_por,
            'fecha_creacion' => $recepcion->fecha_creacion ? $recepcion->fecha_creacion->toIso8601String() : null,
            'proveedor' => [
                'proveedor_id' => $recepcion->id_proveedor ?? 'N/A',
                'nombre' => $proveedor?->nombre_empresa ?? $proveedor?->nombre ?? 'Proveedor Externo',
                'contacto' => $proveedor?->email ?? 'N/A',
                'telefono' => $proveedor?->telefono ?? 'N/A',
            ],
            'productos' => $productosFormateados,
            'total_productos' => count($productosFormateados),
            'cantidad_total' => (float)$productos->sum('cantidad_esperada'),
        ];
    }

    public function show(string $id)
    {
        $recepcion = Recepcion::where('codigo_recepcion', $id)
            ->with(['proveedor', 'productos.producto'])
            ->firstOrFail();

        return view('moduloinventario::recepciones.recepcionEtiquetas', compact('recepcion'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
