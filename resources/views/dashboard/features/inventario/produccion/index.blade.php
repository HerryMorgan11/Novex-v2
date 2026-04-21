@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventario.css', 'resources/css/dashboard/features/inventario/produccion.css'])
@endpush

@section('content')
<div class="inv-page-wrapper">

    @include('dashboard.features.inventario.partials.top-nav')

    <div class="inv-page-header">
        <div>
            <h1>Producción</h1>
            <div class="inv-breadcrumb">
                <a href="{{ route('inventario.index') }}" class="inv-breadcrumb-link">Inventario</a>
                &rsaquo; Producción
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="inv-alert inv-alert-success"><iconify-icon icon="lucide:check-circle"></iconify-icon> {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div class="inv-alert inv-alert-error"><iconify-icon icon="lucide:x-circle"></iconify-icon> {{ session('error') }}</div>
    @endif

    {{-- Lotes disponibles para pasar a producción --}}
    <div class="inv-detail-card inv-mb-24">
        <h3>Lotes almacenados — Disponibles para producción ({{ $lotesDisponibles->total() }})</h3>

        @if($lotesDisponibles->isEmpty())
        <p class="inv-list-empty">
            No hay lotes almacenados disponibles para mover a producción.
        </p>
        @else
        <div class="inv-table-wrapper">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th>Lote</th>
                        <th>Producto</th>
                        <th>Categoría</th>
                        <th>Ubicación</th>
                        <th>Disponible</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lotesDisponibles as $lote)
                    <tr>
                        <td class="mono">{{ $lote->numero_lote }}</td>
                        <td>
                            <div class="inv-text-bold">{{ $lote->producto?->nombre ?? '—' }}</div>
                            <div class="inv-text-sm-muted">{{ $lote->producto?->sku ?? '' }}</div>
                        </td>
                        <td class="inv-td-muted">{{ $lote->producto?->categoria?->nombre ?? '—' }}</td>
                        <td class="inv-td-sm">{{ $lote->ubicacion?->codigoCompleto() ?? '—' }}</td>
                        <td class="inv-td-bold">
                            {{ number_format($lote->cantidadDisponible(), 0) }}
                            <span class="inv-text-unit">{{ $lote->producto?->unidadMedida?->abreviatura ?? '' }}</span>
                        </td>
                        <td>
                            <button onclick="abrirModalProduccion({{ $lote->id_lote }}, '{{ $lote->numero_lote }}', '{{ $lote->producto?->nombre }}')"
                                class="inv-btn inv-btn-outline inv-btn-icon">
                                <iconify-icon icon="lucide:factory" width="13"></iconify-icon>
                                Mover a producción
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="inv-pagination inv-mt-12">{{ $lotesDisponibles->links() }}</div>
        @endif
    </div>

    {{-- Lotes en producción --}}
    <div class="inv-detail-card">
        <h3>En producción ({{ $lotesEnProduccion->total() }})</h3>

        @if($lotesEnProduccion->isEmpty())
        <p class="inv-list-empty">
            No hay lotes en producción actualmente.
        </p>
        @else
        <div class="inv-table-wrapper">
            <table class="inv-table">
                <thead>
                    <tr>
                        <th>Lote</th>
                        <th>Producto</th>
                        <th>Enviado a producción</th>
                        <th>Trazabilidad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lotesEnProduccion as $lote)
                    <tr>
                        <td class="mono">{{ $lote->numero_lote }}</td>
                        <td>
                            <div class="inv-text-bold">{{ $lote->producto?->nombre ?? '—' }}</div>
                            <span class="badge badge-purple">en producción</span>
                        </td>
                        <td class="inv-td-muted">{{ $lote->updated_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('inventario.trazabilidad.historial', $lote->id_lote) }}" class="inv-btn inv-btn-ghost inv-btn-icon">
                                <iconify-icon icon="lucide:list-tree" width="13"></iconify-icon>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="inv-pagination inv-mt-12">{{ $lotesEnProduccion->links() }}</div>
        @endif
    </div>

</div>

{{-- Modal confirmación producción --}}
<div id="modal-produccion" hidden class="inv-modal-overlay">
    <div class="inv-modal-dialog-md">
        <h3 class="inv-modal-title-sm">Mover a producción</h3>
        <p id="modal-prod-nombre" class="inv-modal-desc"></p>
        <div class="inv-alert inv-alert-warning inv-modal-alert-mb">
            <iconify-icon icon="lucide:alert-triangle"></iconify-icon>
            El lote saldrá completamente del inventario de almacén.
        </div>
        <form id="form-produccion" method="POST" action="">
            @csrf
            <div class="inv-form-group inv-form-mb">
                <label>Observaciones (opcional)</label>
                <textarea name="observaciones" rows="2"></textarea>
            </div>
            <div class="inv-modal-footer">
                <button type="button" onclick="cerrarModalProd()" class="inv-btn inv-btn-outline">Cancelar</button>
                <button type="submit" class="inv-btn inv-btn-primary">
                    <iconify-icon icon="lucide:factory"></iconify-icon>
                    Confirmar
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function abrirModalProduccion(loteId, numeroLote, nombreProducto) {
    document.getElementById('modal-prod-nombre').textContent = `${nombreProducto} · ${numeroLote}`;
    document.getElementById('form-produccion').action = `/dashboard/features/inventario/produccion/lotes/${loteId}/mover`;
    const modal = document.getElementById('modal-produccion');
    modal.removeAttribute('hidden');
    modal.style.display = 'flex';
}

function cerrarModalProd() {
    const modal = document.getElementById('modal-produccion');
    modal.setAttribute('hidden', '');
    modal.style.display = 'none';
}
</script>
@endpush
@endsection
