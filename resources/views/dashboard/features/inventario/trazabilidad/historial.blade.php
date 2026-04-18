@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventario.css'])
@endpush

@section('content')
<div style="padding: 20px 0;">

    <div class="inv-page-header">
        <div>
            <h1>Trazabilidad — {{ $lote->numero_lote }}</h1>
            <div class="inv-breadcrumb">
                <a href="{{ route('inventario.index') }}" style="color:var(--muted); text-decoration:none;">Inventario</a>
                &rsaquo;
                <a href="{{ route('inventario.stock.index') }}" style="color:var(--muted); text-decoration:none;">Stock</a>
                &rsaquo;
                <a href="{{ route('inventario.stock.show', $lote->id_lote) }}" style="color:var(--muted); text-decoration:none;">{{ $lote->numero_lote }}</a>
                &rsaquo; Trazabilidad
            </div>
        </div>
        @php $color = $lote->estado?->color() ?? 'secondary' @endphp
        <span class="badge badge-{{ $color }}" style="font-size:0.8rem; padding:6px 14px;">
            Estado actual: {{ $lote->estado?->label() ?? $lote->estado }}
        </span>
    </div>

    {{-- Resumen del lote --}}
    <div class="inv-detail-card" style="margin-bottom:24px;">
        <div class="inv-detail-grid">
            <div class="inv-detail-item">
                <label>Lote</label>
                <span style="font-family:monospace;">{{ $lote->numero_lote }}</span>
            </div>
            <div class="inv-detail-item">
                <label>Producto</label>
                <span>{{ $lote->producto?->nombre ?? '—' }}</span>
            </div>
            <div class="inv-detail-item">
                <label>SKU</label>
                <span style="font-family:monospace;">{{ $lote->producto?->sku ?? '—' }}</span>
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
    <div style="max-width:680px;">
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
                        <span style="color:var(--muted-2);">{{ $evento->estado_anterior }}</span>
                        <iconify-icon icon="lucide:arrow-right" width="11" style="margin:0 4px;"></iconify-icon>
                        @endif
                        <span class="badge badge-{{ match($evento->estado_nuevo) {
                            'stored','delivered' => 'success',
                            'pending_inbound','received' => 'warning',
                            'dispatched' => 'primary',
                            'in_production' => 'purple',
                            'blocked','incident' => 'danger',
                            default => 'secondary'
                        } }}" style="font-size:0.65rem;">{{ $evento->estado_nuevo }}</span>

                        @if($evento->observaciones)
                        <div style="margin-top:6px;">{{ $evento->observaciones }}</div>
                        @endif

                        <div style="display:flex; gap:12px; margin-top:6px; font-size:0.72rem; color:var(--muted-2);">
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
