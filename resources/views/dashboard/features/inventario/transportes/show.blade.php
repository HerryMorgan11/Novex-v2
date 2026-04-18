@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventario.css'])
@endpush

@section('content')
<div style="padding: 20px 0;">

    <div class="inv-page-header">
        <div>
            <h1>{{ $transporte->codigo_recepcion }}</h1>
            <div class="inv-breadcrumb">
                <a href="{{ route('inventario.index') }}" style="color:var(--muted); text-decoration:none;">Inventario</a>
                &rsaquo;
                <a href="{{ route('inventario.transportes.index') }}" style="color:var(--muted); text-decoration:none;">Transportes</a>
                &rsaquo; {{ $transporte->codigo_recepcion }}
            </div>
        </div>
        @php $color = $transporte->estado?->color() ?? 'secondary' @endphp
        <span class="badge badge-{{ $color }}" style="font-size:0.8rem; padding:6px 14px;">
            {{ $transporte->estado?->label() ?? $transporte->estado }}
        </span>
    </div>

    @if(session('success'))
    <div class="inv-alert inv-alert-success"><iconify-icon icon="lucide:check-circle"></iconify-icon> {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="inv-alert inv-alert-error"><iconify-icon icon="lucide:x-circle"></iconify-icon> {{ session('error') }}</div>
    @endif

    {{-- Datos del transporte --}}
    <div class="inv-detail-card">
        <h3>Datos del transporte</h3>
        <div class="inv-detail-grid">
            <div class="inv-detail-item">
                <label>Referencia</label>
                <span style="font-family:monospace;">{{ $transporte->codigo_recepcion }}</span>
            </div>
            <div class="inv-detail-item">
                <label>Origen evento</label>
                <span class="badge {{ $transporte->origen_evento === 'api' ? 'badge-primary' : 'badge-secondary' }}">
                    {{ strtoupper($transporte->origen_evento ?? 'manual') }}
                </span>
            </div>
            @if($transporte->origen)
            <div class="inv-detail-item">
                <label>Origen</label>
                <span>{{ $transporte->origen }}</span>
            </div>
            @endif
            @if($transporte->destino)
            <div class="inv-detail-item">
                <label>Destino</label>
                <span>{{ $transporte->destino }}</span>
            </div>
            @endif
            @if($transporte->transportista)
            <div class="inv-detail-item">
                <label>Transportista</label>
                <span>{{ $transporte->transportista }}</span>
            </div>
            @endif
            @if($transporte->patente)
            <div class="inv-detail-item">
                <label>Matrícula</label>
                <span style="font-family:monospace;">{{ $transporte->patente }}</span>
            </div>
            @endif
            <div class="inv-detail-item">
                <label>Fecha prevista</label>
                <span>{{ $transporte->fecha_estimada?->format('d/m/Y H:i') ?? '—' }}</span>
            </div>
            <div class="inv-detail-item">
                <label>Fecha recepción</label>
                <span>{{ $transporte->fecha_recepcion?->format('d/m/Y H:i') ?? '—' }}</span>
            </div>
            @if($transporte->observaciones)
            <div class="inv-detail-item" style="grid-column: 1 / -1;">
                <label>Observaciones</label>
                <span>{{ $transporte->observaciones }}</span>
            </div>
            @endif
        </div>
    </div>

    {{-- Líneas del transporte --}}
    <div class="inv-detail-card">
        <h3>Líneas del transporte ({{ $transporte->lineas->count() }})</h3>

        @if($transporte->lineas->isEmpty())
        <p style="font-size:0.85rem; color:var(--muted);">Sin líneas registradas.</p>
        @else
        <div class="inv-table-wrapper">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th>Referencia</th>
                        <th>Producto</th>
                        <th>Lote</th>
                        <th>Cant. esperada</th>
                        <th>Unidad</th>
                        <th>Estado</th>
                        <th>Ubicación</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transporte->lineas as $linea)
                    <tr>
                        <td class="mono">{{ $linea->codigoProducto() }}</td>
                        <td>
                            <div style="font-weight:500;">{{ $linea->nombreProducto() }}</div>
                            @if($linea->producto?->esBorrador())
                            <span class="badge badge-warning" style="font-size:0.65rem; margin-top:3px;">borrador</span>
                            @endif
                        </td>
                        <td class="mono">{{ $linea->lote?->numero_lote ?? '—' }}</td>
                        <td style="font-weight:500;">{{ number_format($linea->cantidad_esperada, 0) }}</td>
                        <td style="color:var(--muted);">{{ $linea->unidad ?? '—' }}</td>
                        <td>
                            @php
                                $estadoColor = match($linea->estado_linea) {
                                    'ubicada'    => 'success',
                                    'recibida'   => 'info',
                                    'incidencia' => 'danger',
                                    default      => 'warning',
                                };
                            @endphp
                            <span class="badge badge-{{ $estadoColor }}">{{ $linea->estado_linea }}</span>
                        </td>
                        <td style="font-size:0.8rem; color:var(--muted);">
                            {{ $linea->lote?->ubicacion?->codigoCompleto() ?? '—' }}
                        </td>
                        <td>
                            @if($linea->estado_linea === 'ubicada')
                            <span style="font-size:0.8rem; color:var(--muted);">Completado</span>
                            @else
                            <button onclick="abrirModalUbicacion({{ $linea->id }}, '{{ $linea->nombreProducto() }}')"
                                class="inv-btn inv-btn-outline" style="font-size:0.78rem; padding:5px 12px;">
                                <iconify-icon icon="lucide:map-pin" width="13"></iconify-icon>
                                Ubicar
                            </button>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

</div>

{{-- Modal de asignación de ubicación --}}
<div id="modal-ubicacion" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:1000; display:flex; align-items:center; justify-content:center;" hidden>
    <div style="background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:28px; width:440px; max-width:95vw;">
        <h3 style="font-size:1rem; font-weight:600; margin-bottom:4px;">Asignar ubicación</h3>
        <p id="modal-producto-nombre" style="font-size:0.85rem; color:var(--muted); margin-bottom:20px;"></p>

        <form id="form-ubicacion" method="POST" action="">
            @csrf
            <div class="inv-form-group" style="margin-bottom:16px;">
                <label>Ubicación en almacén</label>
                <select name="id_ubicacion" class="inv-select" style="width:100%;" required>
                    <option value="">Seleccionar ubicación...</option>
                    @foreach($ubicaciones as $ub)
                    <option value="{{ $ub['id'] }}">{{ $ub['codigo'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="inv-form-group" style="margin-bottom:20px;">
                <label>Observaciones (opcional)</label>
                <textarea name="observaciones" rows="2" style="resize:vertical;"></textarea>
            </div>

            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" onclick="cerrarModal()" class="inv-btn inv-btn-outline">Cancelar</button>
                <button type="submit" class="inv-btn inv-btn-primary">
                    <iconify-icon icon="lucide:check"></iconify-icon>
                    Confirmar recepción
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const baseUrl = "{{ route('inventario.transportes.show', $transporte->id_recepcion) }}";

function abrirModalUbicacion(lineaId, productoNombre) {
    document.getElementById('modal-producto-nombre').textContent = productoNombre;
    document.getElementById('form-ubicacion').action = baseUrl + '/lineas/' + lineaId + '/recibir';
    const modal = document.getElementById('modal-ubicacion');
    modal.removeAttribute('hidden');
    modal.style.display = 'flex';
}

function cerrarModal() {
    const modal = document.getElementById('modal-ubicacion');
    modal.setAttribute('hidden', '');
    modal.style.display = 'none';
}
</script>
@endpush
@endsection
