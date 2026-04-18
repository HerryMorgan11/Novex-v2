<?php

namespace App\Enums\Inventario;

enum MovimientoTipo: string
{
    case Recepcion = 'recepcion';
    case Ubicacion = 'ubicacion';
    case Traslado = 'traslado';
    case Produccion = 'produccion';
    case Reparto = 'reparto';
    case Entrega = 'entrega';
    case Ajuste = 'ajuste';
    case Incidencia = 'incidencia';
    case Bloqueo = 'bloqueo';

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
