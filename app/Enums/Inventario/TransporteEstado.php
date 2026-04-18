<?php

namespace App\Enums\Inventario;

enum TransporteEstado: string
{
    case Anunciado = 'anunciado';
    case Recibido = 'recibido';
    case PendienteUbicacion = 'pendiente_ubicacion';
    case Ubicado = 'ubicado';
    case Completado = 'completado';
    case Cancelado = 'cancelado';
    case Incidencia = 'incidencia';

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
