@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventario/general-inventario.css', 'resources/css/dashboard/features/inventario/transportes.css'])
@endpush

@section('content')
<div class="inv-page-wrapper">

    @include('dashboard.features.inventario.partials.top-nav')

    <div class="inv-page-header">
        <div>
            <h1>{{ $transporte->codigo_recepcion }}</h1>
            <div class="inv-breadcrumb">
                <a href="{{ route('inventario.index') }}" class="inv-breadcrumb-link">Inventario</a>
                &rsaquo;
                <a href="{{ route('inventario.transportes.index') }}" class="inv-breadcrumb-link">Transportes</a>
                &rsaquo; {{ $transporte->codigo_recepcion }}
            </div>
        </div>
        @php $color = $transporte->estado?->color() ?? 'secondary' @endphp
        <span class="badge badge-{{ $color }} inv-badge-lg">
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
                <span class="inv-mono">{{ $transporte->codigo_recepcion }}</span>
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
                <span class="inv-mono">{{ $transporte->patente }}</span>
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
            <div class="inv-detail-item inv-detail-item-full">
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
        <p class="inv-card-muted-text">Sin líneas registradas.</p>
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
                            <div class="inv-text-bold">{{ $linea->nombreProducto() }}</div>
                            @if($linea->producto?->esBorrador())
                            <span class="badge badge-warning inv-badge-xs inv-badge-mt">borrador</span>
                            @endif
                        </td>
                        <td class="mono">{{ $linea->lote?->numero_lote ?? '—' }}</td>
                        <td class="inv-td-bold">{{ number_format($linea->cantidad_esperada, 0) }}</td>
                        <td class="inv-td-muted">{{ $linea->unidad ?? '—' }}</td>
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
                        <td class="inv-td-muted">
                            {{ $linea->lote?->ubicacion?->codigoCompleto() ?? '—' }}
                        </td>
                        <td>
                            @if($linea->estado_linea === 'ubicada')
                            <span class="inv-td-muted">Completado</span>
                            @else
                            <button
                                type="button"
                                onclick="abrirModalUbicacion({{ $linea->id }})"
                                class="inv-btn inv-btn-outline inv-btn-icon">
                                <iconify-icon icon="lucide:map-pin" width="13"></iconify-icon>
                                Ubicar
                            </button>

                            <div id="modal-ubicacion-{{ $linea->id }}" class="modal-ubicacion-linea inv-modal-overlay" hidden>
                                <div class="inv-modal-dialog-lg">
                                    <h3 class="inv-modal-title-sm">Asignar ubicación</h3>
                                    <p class="inv-modal-desc-lg">{{ $linea->nombreProducto() }}</p>

                                    <form method="POST" action="{{ route('inventario.transportes.lineas.recibir', [$transporte->id_recepcion, $linea->id]) }}">
                                        @csrf
                                        <div class="inv-form-group inv-form-mb">
                                            <label>Ubicación en almacén</label>
                                            <select name="id_ubicacion" class="inv-select inv-full-width" required>
                                                <option value="">Seleccionar ubicación...</option>
                                                @foreach($ubicaciones as $almacen => $grupo)
                                                <optgroup label="{{ $almacen }}">
                                                    @foreach($grupo as $ub)
                                                    <option value="{{ $ub['id'] }}">
                                                        {{ $ub['codigo'] }}
                                                        @if($ub['zona'] || $ub['estanteria'])
                                                            ({{ $ub['zona'] ?? 'Sin zona' }} · Estantería {{ $ub['estanteria'] ?? '—' }})
                                                        @endif
                                                    </option>
                                                    @endforeach
                                                </optgroup>
                                                @endforeach
                                            </select>
                                            @if($ubicaciones->isEmpty())
                                            <p class="inv-hint-text">
                                                No hay ubicaciones creadas. Crea un almacén, una zona, una estantería y una ubicación para poder confirmar la recepción.
                                            </p>
                                            @endif
                                        </div>

                                        <div class="inv-form-group inv-mb-20">
                                            <label>Observaciones (opcional)</label>
                                            <textarea name="observaciones" rows="2" class="inv-textarea-resizable"></textarea>
                                        </div>

                                        <div class="inv-modal-footer">
                                            <a href="{{ route('inventario.almacenes.create') }}" class="inv-btn inv-btn-outline">
                                                <iconify-icon icon="lucide:warehouse"></iconify-icon>
                                                Crear almacén
                                            </a>
                                            <button type="button" onclick="cerrarModalUbicacion({{ $linea->id }})" class="inv-btn inv-btn-outline">Cancelar</button>
                                            <button type="submit" class="inv-btn inv-btn-primary">
                                                <iconify-icon icon="lucide:check"></iconify-icon>
                                                Confirmar recepción
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
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

@push('scripts')
<script>
function abrirModalUbicacion(lineaId) {
    const modal = document.getElementById('modal-ubicacion-' + lineaId);
    modal.removeAttribute('hidden');
    modal.style.display = 'flex';
}

function cerrarModalUbicacion(lineaId) {
    const modal = document.getElementById('modal-ubicacion-' + lineaId);
    modal.setAttribute('hidden', '');
    modal.style.display = 'none';
}
</script>
@endpush
@endsection
