<?php

namespace App\Enums\Inventario;

/**
 * Estados posibles de una expedición en el módulo de inventario.
 */
enum ExpedicionEstado: string
{
    /** Expedición preparada, pendiente de envío. */
    case Preparada = 'preparada';
    /** Expedición enviada al transportista. */
    case Expedida = 'expedida';
    /** Expedición en camino hacia el destino. */
    case EnTransito = 'en_transito';
    /** Expedición recibida en destino. */
    case Entregada = 'entregada';
    /** Expedición cancelada. */
    case Cancelada = 'cancelada';

    /**
     * Devuelve la etiqueta legible del estado.
     */
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

    /**
     * Devuelve la clase de color asociada al estado.
     */
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
