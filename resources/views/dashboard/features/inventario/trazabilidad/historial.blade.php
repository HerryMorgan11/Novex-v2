@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventario/general-inventario.css', 'resources/css/dashboard/features/inventario/trazabilidad.css'])
@endpush

@section('content')
<div class="inv-page-wrapper">

    @include('dashboard.features.inventario.partials.top-nav')

    <div class="inv-page-header">
        <div>
            <h1>Trazabilidad — {{ $lote->numero_lote }}</h1>
            <div class="inv-breadcrumb">
                <a href="{{ route('inventario.index') }}" class="inv-breadcrumb-link">Inventario</a>
                &rsaquo;
                <a href="{{ route('inventario.stock.index') }}" class="inv-breadcrumb-link">Stock</a>
                &rsaquo;
                <a href="{{ route('inventario.stock.show', $lote->id_lote) }}" class="inv-breadcrumb-link">{{ $lote->numero_lote }}</a>
                &rsaquo; Trazabilidad
            </div>
        </div>
        @php $color = $lote->estado?->color() ?? 'secondary' @endphp
        <span class="badge badge-{{ $color }} inv-badge-lg">
            Estado actual: {{ $lote->estado?->label() ?? $lote->estado }}
        </span>
    </div>

    {{-- Resumen del lote --}}
    <div class="inv-detail-card inv-mb-24">
        <div class="inv-detail-grid">
            <div class="inv-detail-item">
                <label>Lote</label>
                <span class="inv-mono">{{ $lote->numero_lote }}</span>
            </div>
            <div class="inv-detail-item">
                <label>Producto</label>
                <span>{{ $lote->producto?->nombre ?? '—' }}</span>
            </div>
            <div class="inv-detail-item">
                <label>SKU</label>
                <span class="inv-mono">{{ $lote->producto?->sku ?? '—' }}</span>
            </div>
            <div class="inv-detail-item">
                <label>Ubicación actual</label>
                <span>{{ $lote->ubicacion?->codigoCompleto() ?? '—' }}</span>
            </div>
        </div>
    </div>

    {{-- Timeline --}}
    @if($eventos->isEmpty())
    <div class="inv-empty">
        <iconify-icon icon="lucide:list-tree"></iconify-icon>
        <p>No hay eventos de trazabilidad registrados para este lote.</p>
    </div>
    @else
    <div class="inv-timeline-wrapper">
        <div class="timeline">
            @foreach($eventos as $evento)
            @php
                $dotColor = match(true) {
                    in_array($evento->tipo_evento, ['entregado'])                => 'dot-success',
                    in_array($evento->tipo_evento, ['recepcion', 'ubicacion'])  => 'dot-info',
                    in_array($evento->tipo_evento, ['incidencia', 'bloqueado']) => 'dot-danger',
                    in_array($evento->tipo_evento, ['produccion'])              => 'dot-purple',
                    in_array($evento->tipo_evento, ['expedido'])                => 'dot-primary',
                    default                                                     => '',
                };
            @endphp
            <div class="timeline-item">
                <div class="timeline-dot {{ $dotColor }}">
                    <iconify-icon icon="{{ $evento->iconoEvento() }}" width="10"></iconify-icon>
                </div>
                <div class="timeline-content">
                    <div class="tl-header">
                        <div class="tl-title">{{ ucfirst(str_replace('_', ' ', $evento->tipo_evento)) }}</div>
                        <div class="tl-time">{{ $evento->fecha_evento->format('d/m/Y H:i') }}</div>
                    </div>
                    <div class="tl-body">
                        @if($evento->estado_anterior)
                        <span class="inv-tl-estado-anterior">{{ $evento->estado_anterior }}</span>
                        <iconify-icon icon="lucide:arrow-right" width="11" class="inv-tl-arrow"></iconify-icon>
                        @endif
                        <span class="badge inv-badge-xs badge-{{ match($evento->estado_nuevo) {
                            'stored','delivered' => 'success',
                            'pending_inbound','received' => 'warning',
                            'dispatched' => 'primary',
                            'in_production' => 'purple',
                            'blocked','incident' => 'danger',
                            default => 'secondary'
                        } }}">{{ $evento->estado_nuevo }}</span>

                        @if($evento->observaciones)
                        <div class="inv-tl-obs">{{ $evento->observaciones }}</div>
                        @endif

                        <div class="inv-tl-meta">
                            <span>
                                <iconify-icon icon="{{ $evento->origen_evento === 'api' ? 'lucide:globe' : 'lucide:user' }}" width="11"></iconify-icon>
                                {{ $evento->origen_evento }}
                            </span>
                            @if($evento->recepcion)
                            <span>
                                <iconify-icon icon="lucide:truck" width="11"></iconify-icon>
                                {{ $evento->recepcion->codigo_recepcion }}
                            </span>
                            @endif
                            @if($evento->expedicion)
                            <span>
                                <iconify-icon icon="lucide:send" width="11"></iconify-icon>
                                {{ $evento->expedicion->referencia_expedicion }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

</div>
@endsection
