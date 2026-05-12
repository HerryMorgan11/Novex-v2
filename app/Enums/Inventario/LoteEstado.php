<?php

namespace App\Enums\Inventario;

/**
 * Estados del ciclo de vida de un lote en el módulo de inventario.
 *
 * Define las transiciones válidas entre estados mediante {@see allowedTransitions()}.
 */
enum LoteEstado: string
{
    /** Lote anunciado, pendiente de recepción física. */
    case PendingInbound = 'pending_inbound';
    /** Lote recibido en el almacén. */
    case Received = 'received';
    /** Lote almacenado en una ubicación. */
    case Stored = 'stored';
    /** Lote en uso dentro de producción. */
    case InProduction = 'in_production';
    /** Lote preparado para expedición. */
    case ReadyForDispatch = 'ready_for_dispatch';
    /** Lote expedido (en ruta). */
    case Dispatched = 'dispatched';
    /** Lote entregado al destinatario. */
    case Delivered = 'delivered';
    /** Lote bloqueado (calidad, incidencia, etc.). */
    case Blocked = 'blocked';
    /** Lote con incidencia abierta. */
    case Incident = 'incident';

    /**
     * Devuelve la etiqueta legible del estado.
     */
    public function label(): string
    {
        return match ($this) {
            self::PendingInbound => 'Pendiente de recepción',
            self::Received => 'Recibido',
            self::Stored => 'Almacenado',
            self::InProduction => 'En producción',
            self::ReadyForDispatch => 'Preparado para reparto',
            self::Dispatched => 'Expedido',
            self::Delivered => 'Entregado',
            self::Blocked => 'Bloqueado',
            self::Incident => 'Con incidencia',
        };
    }

    /**
     * Devuelve la clase de color asociada al estado.
     */
    public function color(): string
    {
        return match ($this) {
            self::PendingInbound => 'warning',
            self::Received => 'info',
            self::Stored => 'success',
            self::InProduction => 'purple',
            self::ReadyForDispatch => 'secondary',
            self::Dispatched => 'primary',
            self::Delivered => 'success',
            self::Blocked => 'danger',
            self::Incident => 'danger',
        };
    }

    /**
     * Transiciones permitidas desde este estado.
     *
     * @return self[]
     */
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::PendingInbound => [self::Received, self::Blocked, self::Incident],
            self::Received => [self::Stored, self::Blocked, self::Incident],
            self::Stored => [self::InProduction, self::ReadyForDispatch, self::Blocked, self::Incident],
            self::InProduction => [self::Delivered, self::Blocked, self::Incident],
            self::ReadyForDispatch => [self::Dispatched, self::Stored, self::Blocked],
            self::Dispatched => [self::Delivered, self::Incident],
            self::Delivered => [],
            self::Blocked => [self::Stored, self::Incident],
            self::Incident => [self::Stored, self::Blocked],
        };
    }

    /**
     * Comprueba si se puede transicionar a un nuevo estado.
     */
    public function canTransitionTo(self $new): bool
    {
        return in_array($new, $this->allowedTransitions(), true);
    }
}
