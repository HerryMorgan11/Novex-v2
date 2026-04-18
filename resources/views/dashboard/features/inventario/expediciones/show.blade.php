@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventario.css'])
@endpush

@section('content')
<div style="padding: 20px 0;">

    <div class="inv-page-header">
        <div>
            <h1>{{ $expedicion->referencia_expedicion }}</h1>
            <div class="inv-breadcrumb">
                <a href="{{ route('inventario.index') }}" style="color:var(--muted); text-decoration:none;">Inventario</a>
                &rsaquo;
                <a href="{{ route('inventario.expediciones.index') }}" style="color:var(--muted); text-decoration:none;">Expediciones</a>
                &rsaquo; {{ $expedicion->referencia_expedicion }}
            </div>
        </div>
        @php $color = $expedicion->estado?->color() ?? 'secondary' @endphp
        <span class="badge badge-{{ $color }}" style="font-size:0.8rem; padding:6px 14px;">
            {{ $expedicion->estado?->label() ?? $expedicion->estado }}
        </span>
    </div>

    @if(session('success'))
    <div class="inv-alert inv-alert-success"><iconify-icon icon="lucide:check-circle"></iconify-icon> {{ session('success') }}</div>
    @endif

    <div style="display:grid; grid-template-columns:2fr 1fr; gap:20px; align-items:start;">

        {{-- Detalle --}}
        <div>
            <div class="inv-detail-card">
                <h3>Datos de la expedición</h3>
                <div class="inv-detail-grid">
                    <div class="inv-detail-item">
                        <label>Referencia</label>
                        <span style="font-family:monospace;">{{ $expedicion->referencia_expedicion }}</span>
                    </div>
                    <div class="inv-detail-item">
                        <label>Tipo</label>
                        <span class="badge {{ $expedicion->tipo === 'reparto' ? 'badge-primary' : 'badge-purple' }}">{{ $expedicion->tipo }}</span>
                    </div>
                    <div class="inv-detail-item">
                        <label>Destino</label>
                        <span>{{ $expedicion->destino ?? '—' }}</span>
                    </div>
                    <div class="inv-detail-item">
                        <label>Vehículo</label>
                        <span style="font-family:monospace;">{{ $expedicion->vehiculo ?? '—' }}</span>
                    </div>
                    <div class="inv-detail-item">
                        <label>Conductor</label>
                        <span>{{ $expedicion->conductor ?? '—' }}</span>
                    </div>
                    <div class="inv-detail-item">
                        <label>Fecha salida</label>
                        <span>{{ $expedicion->fecha_salida?->format('d/m/Y H:i') ?? '—' }}</span>
                    </div>
                    <div class="inv-detail-item">
                        <label>Confirmación entrega</label>
                        <span>{{ $expedicion->fecha_confirmacion_entrega?->format('d/m/Y H:i') ?? '—' }}</span>
                    </div>
                    @if($expedicion->observaciones)
                    <div class="inv-detail-item" style="grid-column:1/-1;">
                        <label>Observaciones</label>
                        <span>{{ $expedicion->observaciones }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Líneas --}}
            <div class="inv-detail-card">
                <h3>Líneas de la expedición ({{ $expedicion->lineas->count() }})</h3>
                <div class="inv-table-wrapper">
                    <table class="inv-table">
                        <thead>
                            <tr>
                                <th>Lote</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                                <th>Ubicación origen</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($expedicion->lineas as $linea)
                            <tr>
                                <td class="mono">{{ $linea->lote?->numero_lote ?? '—' }}</td>
                                <td>{{ $linea->producto?->nombre ?? $linea->lote?->producto?->nombre ?? '—' }}</td>
                                <td style="font-weight:600;">{{ number_format($linea->cantidad, 0) }}</td>
                                <td style="color:var(--muted);">{{ $linea->unidad ?? '—' }}</td>
                                <td style="font-size:0.8rem; color:var(--muted);">
                                    {{ $linea->lote?->ubicacion?->codigoCompleto() ?? '—' }}
                                </td>
                                <td>
                                    @php $ec = match($linea->estado) { 'entregada'=>'success', 'expedida'=>'primary', default=>'secondary' } @endphp
                                    <span class="badge badge-{{ $ec }}">{{ $linea->estado }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Panel token --}}
        <div>
            <div class="inv-detail-card">
                <h3>Confirmación externa</h3>
                <p style="font-size:0.82rem; color:var(--muted); margin-bottom:12px;">
                    Endpoint para que el destinatario confirme la recepción de la mercancía:
                </p>
                <div style="background:var(--surface-2); border:1px solid var(--border); border-radius:8px; padding:12px; font-family:monospace; font-size:0.75rem; word-break:break-all; color:var(--fg); margin-bottom:12px;">
                    POST /api/inventario/expediciones/{{ $expedicion->referencia_expedicion }}/confirmar-entrega
                </div>
                @if($expedicion->estado?->value !== 'entregada')
                <p style="font-size:0.75rem; color:var(--muted);">
                    Estado actual: <span class="badge badge-{{ $color }}">{{ $expedicion->estado?->label() }}</span>
                </p>
                @else
                <p style="font-size:0.82rem; color:#15803d; font-weight:500;">
                    <iconify-icon icon="lucide:check-circle"></iconify-icon>
                    Entrega confirmada el {{ $expedicion->fecha_confirmacion_entrega?->format('d/m/Y H:i') }}
                </p>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
