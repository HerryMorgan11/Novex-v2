<?php

namespace App\Enums\Inventario;

/**
 * Estados posibles de un transporte entrante en el módulo de inventario.
 */
enum TransporteEstado: string
{
    /** Transporte anunciado por el proveedor. */
    case Anunciado = 'anunciado';
    /** Transporte recibido físicamente en el almacén. */
    case Recibido = 'recibido';
    /** Mercancía pendiente de ubicar en estantería. */
    case PendienteUbicacion = 'pendiente_ubicacion';
    /** Mercancía ubicada en su posición. */
    case Ubicado = 'ubicado';
    /** Transporte procesado por completo. */
    case Completado = 'completado';
    /** Transporte cancelado. */
    case Cancelado = 'cancelado';
    /** Transporte con incidencia abierta. */
    case Incidencia = 'incidencia';

    /**
     * Devuelve la etiqueta legible del estado.
     */
    public function label(): string
    {
        return match ($this) {
            self::Anunciado => 'Anunciado',
            self::Recibido => 'Recibido',
            self::PendienteUbicacion => 'Pendiente de ubicación',
            self::Ubicado => 'Ubicado',
            self::Completado => 'Completado',
            self::Cancelado => 'Cancelado',
            self::Incidencia => 'Con incidencia',
        };
    }

    /**
     * Devuelve la clase de color asociada al estado.
     */
    public function color(): string
    {
        return match ($this) {
            self::Anunciado => 'warning',
            self::Recibido => 'info',
            self::PendienteUbicacion => 'secondary',
            self::Ubicado => 'primary',
            self::Completado => 'success',
            self::Cancelado => 'neutral',
            self::Incidencia => 'danger',
        };
    }
}
