<?php

namespace Database\Seeders;

use App\Models\Inventario\Almacen;
use App\Models\Inventario\ApiTokenInventario;
use App\Models\Inventario\CategoriaProducto;
use App\Models\Inventario\Estanteria;
use App\Models\Inventario\Producto;
use App\Models\Inventario\Ubicacion;
use App\Models\Inventario\UnidadMedida;
use App\Models\Inventario\Zona;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class InventarioSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Unidades de medida
        $unidades = [
            ['nombre' => 'Pieza',      'abreviatura' => 'pcs',  'factor_conversion' => 1],
            ['nombre' => 'Kilogramo',  'abreviatura' => 'kg',   'factor_conversion' => 1],
            ['nombre' => 'Litro',      'abreviatura' => 'L',    'factor_conversion' => 1],
            ['nombre' => 'Metro',      'abreviatura' => 'm',    'factor_conversion' => 1],
            ['nombre' => 'Caja',       'abreviatura' => 'caja', 'factor_conversion' => 1],
            ['nombre' => 'Palet',      'abreviatura' => 'plt',  'factor_conversion' => 1],
        ];

        foreach ($unidades as $u) {
            UnidadMedida::firstOrCreate(['abreviatura' => $u['abreviatura']], $u);
        }

        // 2. Categorías de producto
        $cats = [
            ['nombre' => 'Materias primas'],
            ['nombre' => 'Componentes'],
            ['nombre' => 'Productos terminados'],
            ['nombre' => 'Fungibles y consumibles'],
            ['nombre' => 'Herramientas'],
        ];

        foreach ($cats as $c) {
            CategoriaProducto::firstOrCreate(['nombre' => $c['nombre']], $c);
        }

        // 3. Almacén + estructura física
        $almacen = Almacen::firstOrCreate(
            ['nombre' => 'Almacén Central'],
            ['direccion' => 'Polígono Industrial Norte, Nave 1', 'responsable' => 'Jefe de Almacén', 'activo' => true]
        );

        $zonaA = Zona::firstOrCreate(['id_almacen' => $almacen->id_almacen, 'nombre' => 'Zona A']);
        $zonaB = Zona::firstOrCreate(['id_almacen' => $almacen->id_almacen, 'nombre' => 'Zona B']);

        // Estanterías y ubicaciones para Zona A
        foreach (['A01', 'A02', 'A03'] as $codigo) {
            $est = Estanteria::firstOrCreate([
                'id_almacen' => $almacen->id_almacen,
                'id_zona' => $zonaA->id_zona,
                'codigo' => $codigo,
            ]);

            foreach (['N1', 'N2', 'N3'] as $nivel) {
                foreach (['H1', 'H2'] as $hueco) {
                    $ub = Ubicacion::firstOrCreate([
                        'id_estanteria' => $est->id_estanteria,
                        'nivel' => $nivel,
                        'posicion' => $hueco,
                    ], [
                        'capacidad' => 100,
                        'activa' => true,
                    ]);

                    // Generar código si no tiene
                    if (! $ub->codigo_ubicacion) {
                        $ub->update([
                            'codigo_ubicacion' => "{$almacen->nombre}-ZA-{$codigo}-{$nivel}-{$hueco}",
                        ]);
                    }
                }
            }
        }

        // Estantería básica para Zona B
        $estB = Estanteria::firstOrCreate([
            'id_almacen' => $almacen->id_almacen,
            'id_zona' => $zonaB->id_zona,
            'codigo' => 'B01',
        ]);

        $ub = Ubicacion::firstOrCreate([
            'id_estanteria' => $estB->id_estanteria,
            'nivel' => 'N1',
            'posicion' => 'H1',
        ], ['activa' => true]);

        if (! $ub->codigo_ubicacion) {
            $ub->update(['codigo_ubicacion' => 'Almacén Central-ZB-B01-N1-H1']);
        }

        // 4. Productos de ejemplo (activos)
        $categoria = CategoriaProducto::where('nombre', 'Componentes')->first();
        $unidadPieza = UnidadMedida::where('abreviatura', 'pcs')->first();
        $unidadKg = UnidadMedida::where('abreviatura', 'kg')->first();

        $productos = [
            ['sku' => 'SKU-TOR-M8',  'nombre' => 'Tornillo M8 x 25mm',       'id_unidad_medida' => $unidadPieza->id_unidad],
            ['sku' => 'SKU-ACE-304', 'nombre' => 'Acero inoxidable 304 barra', 'id_unidad_medida' => $unidadKg->id_unidad],
            ['sku' => 'SKU-CAJ-001', 'nombre' => 'Caja cartón 60x40x30',      'id_unidad_medida' => $unidadPieza->id_unidad],
        ];

        foreach ($productos as $p) {
            Producto::firstOrCreate(['sku' => $p['sku']], array_merge($p, [
                'id_categoria' => $categoria?->id_categoria,
                'estado' => 'activo',
                'estado_validacion' => 'activo',
                'costo' => 0,
                'precio_referencia' => 0,
            ]));
        }

        // 5. Token de API para integraciones externas (ejemplo)
        $tokenExistente = ApiTokenInventario::where('nombre', 'Sistema de Transportes Externo')->first();
        if (! $tokenExistente) {
            $rawToken = Str::random(40);
            ApiTokenInventario::create([
                'nombre' => 'Sistema de Transportes Externo',
                'token' => $rawToken, // En producción: hash('sha256', $rawToken)
                'permisos' => 'full',
                'activo' => true,
            ]);

            $this->command->info("Token API creado: {$rawToken}");
            $this->command->warn('Guarda este token — no se volverá a mostrar.');
        }

        $this->command->info('InventarioSeeder completado correctamente.');
    }
}
