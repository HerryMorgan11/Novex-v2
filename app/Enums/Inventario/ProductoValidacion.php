<?php

namespace App\Enums\Inventario;

/**
 * Estados de validación/publicación de un producto en el módulo de inventario.
 */
enum ProductoValidacion: string
{
    /** Producto en borrador, no visible para operaciones. */
    case Borrador = 'borrador';
    /** Producto activo y disponible para uso. */
    case Activo = 'activo';
    /** Producto desactivado, ya no se utiliza. */
    case Inactivo = 'inactivo';

    /**
     * Devuelve la etiqueta legible del estado de validación.
     */
    public function label(): string
    {
        return match ($this) {
            self::Borrador => 'Borrador',
            self::Activo => 'Activo',
            self::Inactivo => 'Inactivo',
        };
    }

    /**
     * Devuelve la clase de color asociada al estado.
     */
    public function color(): string
    {
        return match ($this) {
            self::Borrador => 'warning',
            self::Activo => 'success',
            self::Inactivo => 'neutral',
        };
    }
}
