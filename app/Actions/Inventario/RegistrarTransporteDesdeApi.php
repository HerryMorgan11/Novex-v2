<?php

namespace App\Actions\Inventario;

use App\Enums\Inventario\LoteEstado;
use App\Enums\Inventario\ProductoValidacion;
use App\Enums\Inventario\TransporteEstado;
use App\Models\Inventario\LineaTransporte;
use App\Models\Inventario\Lote;
use App\Models\Inventario\Producto;
use App\Models\Inventario\Transporte;
use App\Models\Inventario\TrazabilidadEvento;
use Illuminate\Support\Str;

/**
 * Registra un transporte entrante recibido desde API externa.
 * Crea o vincula productos (como borrador si no existen),
 * genera lotes por línea y los deja en estado pending_inbound.
 */
class RegistrarTransporteDesdeApi
{
    public function ejecutar(array $payload, string $origenEvento = 'api'): Transporte
    {
        $transporte = Transporte::create([
            'codigo_recepcion' => Transporte::generarCodigo(),
            'nombre_camion' => $payload['vehiculo'] ?? $payload['nombre_camion'] ?? null,
            'patente' => $payload['placa'] ?? $payload['patente'] ?? null,
            'origen' => $payload['origen'] ?? null,
            'destino' => $payload['destino'] ?? null,
            'transportista' => $payload['transportista'] ?? null,
            'fecha_estimada' => $payload['fecha_prevista'] ?? $payload['fecha_estimada'] ?? null,
            'estado' => TransporteEstado::Anunciado,
            'origen_evento' => $origenEvento,
            'payload_json' => $payload,
            'creado_por' => $payload['creado_por'] ?? 'api',
            'fecha_creacion' => now(),
        ]);

        foreach ($payload['lineas'] ?? [] as $linea) {
            $this->procesarLinea($transporte, $linea);
        }

        return $transporte->load('lineas.producto', 'lineas.lote');
    }

    private function procesarLinea(Transporte $transporte, array $linea): void
    {
        $producto = null;
        $esBorrador = false;

        // Buscar producto existente por SKU o código de referencia
        if (! empty($linea['referencia_producto'])) {
            $producto = Producto::where('sku', $linea['referencia_producto'])->first();
        }

        // Si no existe, crear como borrador para validación manual
        if ($producto === null) {
            $producto = Producto::create([
                'nombre' => $linea['nombre'] ?? $linea['referencia_producto'] ?? 'Producto sin nombre',
                'sku' => $linea['referencia_producto'] ?? null,
                'estado' => 'activo',
                'estado_validacion' => ProductoValidacion::Borrador,
                'notas_internas' => 'Creado automáticamente desde API el '.now()->toDateTimeString(),
            ]);
            $esBorrador = true;
        }

        // Crear lote para esta línea del transporte
        $lote = Lote::create([
            'id_producto' => $producto->id_producto,
            'numero_lote' => $this->generarNumeroLote($transporte, $producto),
            'estado' => LoteEstado::PendingInbound,
        ]);

        // Crear línea del transporte
        LineaTransporte::create([
            'id_recepcion' => $transporte->id_recepcion,
            'id_producto' => $producto->id_producto,
            'id_lote' => $lote->id_lote,
            'producto_codigo_ref' => $linea['referencia_producto'] ?? null,
            'producto_nombre_ref' => $linea['nombre'] ?? null,
            'cantidad_esperada' => $linea['cantidad'] ?? 0,
            'cantidad_recibida' => 0,
            'unidad' => $linea['unidad'] ?? null,
            'estado_linea' => 'pendiente',
        ]);

        // Registrar evento de trazabilidad
        TrazabilidadEvento::create([
            'id_lote' => $lote->id_lote,
            'id_producto' => $producto->id_producto,
            'tipo_evento' => 'recepcion',
            'estado_anterior' => null,
            'estado_nuevo' => LoteEstado::PendingInbound->value,
            'origen_evento' => 'api',
            'id_recepcion' => $transporte->id_recepcion,
            'observaciones' => 'Anunciado desde API. '.($esBorrador ? 'Producto creado como borrador.' : ''),
            'fecha_evento' => now(),
        ]);
    }

    private function generarNumeroLote(Transporte $transporte, Producto $producto): string
    {
        return sprintf(
            'LOTE-%s-%s-%s',
            $transporte->codigo_recepcion,
            strtoupper(Str::substr($producto->sku ?? 'PRD', 0, 6)),
            now()->format('Ymd')
        );
    }
}
