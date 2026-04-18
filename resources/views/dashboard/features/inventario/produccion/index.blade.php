@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventario.css'])
@endpush

@section('content')
<div style="padding: 20px 0;">

    <div class="inv-page-header">
        <div>
            <h1>Producción</h1>
            <div class="inv-breadcrumb">
                <a href="{{ route('inventario.index') }}" style="color:var(--muted); text-decoration:none;">Inventario</a>
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
    <div class="inv-detail-card" style="margin-bottom:24px;">
        <h3>Lotes almacenados — Disponibles para producción ({{ $lotesDisponibles->total() }})</h3>

        @if($lotesDisponibles->isEmpty())
        <p style="color:var(--muted); font-size:0.85rem; text-align:center; padding:20px 0;">
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
                            <div style="font-weight:500;">{{ $lote->producto?->nombre ?? '—' }}</div>
                            <div style="font-size:0.75rem; color:var(--muted);">{{ $lote->producto?->sku ?? '' }}</div>
                        </td>
                        <td style="font-size:0.82rem; color:var(--muted);">{{ $lote->producto?->categoria?->nombre ?? '—' }}</td>
                        <td style="font-size:0.82rem;">{{ $lote->ubicacion?->codigoCompleto() ?? '—' }}</td>
                        <td style="font-weight:600;">
                            {{ number_format($lote->cantidadDisponible(), 0) }}
                            <span style="font-size:0.75rem; color:var(--muted);">{{ $lote->producto?->unidadMedida?->abreviatura ?? '' }}</span>
                        </td>
                        <td>
                            <button onclick="abrirModalProduccion({{ $lote->id_lote }}, '{{ $lote->numero_lote }}', '{{ $lote->producto?->nombre }}')"
                                class="inv-btn inv-btn-outline" style="font-size:0.78rem; padding:5px 12px;">
                                <iconify-icon icon="lucide:factory" width="13"></iconify-icon>
                                Mover a producción
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $lotesDisponibles->links() }}</div>
        @endif
    </div>

    {{-- Lotes en producción --}}
    <div class="inv-detail-card">
        <h3>En producción ({{ $lotesEnProduccion->total() }})</h3>

        @if($lotesEnProduccion->isEmpty())
        <p style="color:var(--muted); font-size:0.85rem; text-align:center; padding:16px 0;">
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
                            <div style="font-weight:500;">{{ $lote->producto?->nombre ?? '—' }}</div>
                            <span class="badge badge-purple">en producción</span>
                        </td>
                        <td style="font-size:0.82rem; color:var(--muted);">{{ $lote->updated_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('inventario.trazabilidad.historial', $lote->id_lote) }}" class="inv-btn inv-btn-ghost" style="padding:5px 10px; font-size:0.78rem;">
                                <iconify-icon icon="lucide:list-tree" width="13"></iconify-icon>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:12px;">{{ $lotesEnProduccion->links() }}</div>
        @endif
    </div>

</div>

{{-- Modal confirmación producción --}}
<div id="modal-produccion" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.4); z-index:1000; align-items:center; justify-content:center;" hidden>
    <div style="background:var(--card); border:1px solid var(--border); border-radius:var(--radius); padding:28px; width:440px; max-width:95vw;">
        <h3 style="font-size:1rem; font-weight:600; margin-bottom:4px;">Mover a producción</h3>
        <p id="modal-prod-nombre" style="font-size:0.85rem; color:var(--muted); margin-bottom:4px;"></p>
        <div class="inv-alert inv-alert-warning" style="margin-bottom:16px;">
            <iconify-icon icon="lucide:alert-triangle"></iconify-icon>
            El lote saldrá completamente del inventario de almacén.
        </div>
        <form id="form-produccion" method="POST" action="">
            @csrf
            <div class="inv-form-group" style="margin-bottom:20px;">
                <label>Observaciones (opcional)</label>
                <textarea name="observaciones" rows="2"></textarea>
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end;">
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
