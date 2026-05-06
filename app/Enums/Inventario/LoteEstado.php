<?php

namespace App\Enums\Inventario;

enum LoteEstado: string
{
    case PendingInbound = 'pending_inbound';
    case Received = 'received';
    case Stored = 'stored';
    case InProduction = 'in_production';
    case ReadyForDispatch = 'ready_for_dispatch';
    case Dispatched = 'dispatched';
    case Delivered = 'delivered';
    case Blocked = 'blocked';
    case Incident = 'incident';

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

    /** Transiciones permitidas desde este estado */
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

    public function canTransitionTo(self $new): bool
    {
        return in_array($new, $this->allowedTransitions(), true);
    }
}
