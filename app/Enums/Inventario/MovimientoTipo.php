<?php

namespace App\Enums\Inventario;

/**
 * Tipos de movimiento registrados en el módulo de inventario.
 */
enum MovimientoTipo: string
{
    /** Recepción de mercancía entrante. */
    case Recepcion = 'recepcion';
    /** Ubicación de mercancía en almacén. */
    case Ubicacion = 'ubicacion';
    /** Traslado interno entre ubicaciones. */
    case Traslado = 'traslado';
    /** Paso de mercancía a producción. */
    case Produccion = 'produccion';
    /** Preparación de pedido para reparto. */
    case Reparto = 'reparto';
    /** Confirmación de entrega al destinatario. */
    case Entrega = 'entrega';
    /** Ajuste manual de stock. */
    case Ajuste = 'ajuste';
    /** Registro de incidencia. */
    case Incidencia = 'incidencia';
    /** Bloqueo de mercancía. */
    case Bloqueo = 'bloqueo';

    /**
     * Devuelve la etiqueta legible del tipo de movimiento.
     */
    public function label(): string
    {
        return match ($this) {
            self::Recepcion => 'Recepción',
            self::Ubicacion => 'Ubicación',
            self::Traslado => 'Traslado interno',
            self::Produccion => 'Paso a producción',
            self::Reparto => 'Preparación reparto',
            self::Entrega => 'Entrega confirmada',
            self::Ajuste => 'Ajuste de stock',
            self::Incidencia => 'Incidencia',
            self::Bloqueo => 'Bloqueo',
        };
    }
}
