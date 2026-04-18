<?php

namespace App\Enums\Inventario;

enum ProductoValidacion: string
{
    case Borrador = 'borrador';
    case Activo = 'activo';
    case Inactivo = 'inactivo';

    public function label(): string
    {
        return match ($this) {
            self::Borrador => 'Borrador',
            self::Activo => 'Activo',
            self::Inactivo => 'Inactivo',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Borrador => 'warning',
            self::Activo => 'success',
            self::Inactivo => 'neutral',
        };
    }
}
