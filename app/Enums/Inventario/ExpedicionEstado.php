<?php

namespace App\Enums\Inventario;

enum ExpedicionEstado: string
{
    case Preparada = 'preparada';
    case Expedida = 'expedida';
    case EnTransito = 'en_transito';
    case Entregada = 'entregada';
    case Cancelada = 'cancelada';

    public function label(): string
    {
        return match ($this) {
            self::Preparada => 'Preparada',
            self::Expedida => 'Expedida',
            self::EnTransito => 'En tránsito',
            self::Entregada => 'Entregada',
            self::Cancelada => 'Cancelada',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Preparada => 'secondary',
            self::Expedida => 'primary',
            self::EnTransito => 'info',
            self::Entregada => 'success',
            self::Cancelada => 'neutral',
        };
    }
}
