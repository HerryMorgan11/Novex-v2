@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventario.css'])
@endpush

@section('content')
<div style="padding: 20px 0;">

    <div class="inv-page-header">
        <div>
            <h1>{{ $lote->numero_lote }}</h1>
            <div class="inv-breadcrumb">
                <a href="{{ route('inventario.index') }}" style="color:var(--muted); text-decoration:none;">Inventario</a>
                &rsaquo;
                <a href="{{ route('inventario.stock.index') }}" style="color:var(--muted); text-decoration:none;">Stock</a>
                &rsaquo; {{ $lote->numero_lote }}
            </div>
        </div>
        <div style="display:flex; gap:10px;">
            @php $color = $lote->estado?->color() ?? 'secondary' @endphp
            <span class="badge badge-{{ $color }}" style="font-size:0.8rem; padding:6px 14px;">
                {{ $lote->estado?->label() ?? $lote->estado }}
            </span>
            <a href="{{ route('inventario.trazabilidad.historial', $lote->id_lote) }}" class="inv-btn inv-btn-outline">
                <iconify-icon icon="lucide:list-tree"></iconify-icon>
                Trazabilidad
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="inv-alert inv-alert-success"><iconify-icon icon="lucide:check-circle"></iconify-icon> {{ session('success') }}</div>
    @endif

    <div style="display:grid; grid-template-columns:2fr 1fr; gap:20px; align-items:start;">

        {{-- Datos del producto y lote --}}
        <div>
            {{-- Producto --}}
            <div class="inv-detail-card">
                <h3>Producto</h3>
                <div class="inv-detail-grid">
                    <div class="inv-detail-item">
                        <label>Nombre</label>
                        <span>{{ $lote->producto?->nombre ?? '—' }}</span>
                    </div>
                    <div class="inv-detail-item">
                        <label>SKU</label>
                        <span style="font-family:monospace;">{{ $lote->producto?->sku ?? '—' }}</span>
                    </div>
                    <div class="inv-detail-item">
                        <label>Categoría</label>
                        <span>{{ $lote->producto?->categoria?->nombre ?? '—' }}</span>
                    </div>
                    <div class="inv-detail-item">
                        <label>Unidad</label>
                        <span>{{ $lote->producto?->unidadMedida?->nombre ?? '—' }}</span>
                    </div>
                    @if($lote->producto?->descripcion)
                    <div class="inv-detail-item" style="grid-column: 1 / -1;">
                        <label>Descripción</label>
                        <span>{{ $lote->producto->descripcion }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Ubicación --}}
            <div class="inv-detail-card">
                <h3>Ubicación</h3>
                @if($lote->ubicacion)
                <div class="inv-detail-grid">
                    <div class="inv-detail-item">
                        <label>Código</label>
                        <span style="font-family:monospace; font-size:1rem; font-weight:700;">{{ $lote->ubicacion->codigoCompleto() }}</span>
                    </div>
                    <div class="inv-detail-item">
                        <label>Almacén</label>
                        <span>{{ $lote->ubicacion?->estanteria?->almacen?->nombre ?? '—' }}</span>
                    </div>
                    <div class="inv-detail-item">
                        <label>Zona</label>
                        <span>{{ $lote->ubicacion?->estanteria?->zona?->nombre ?? '—' }}</span>
                    </div>
                    <div class="inv-detail-item">
                        <label>Estantería</label>
                        <span>{{ $lote->ubicacion?->estanteria?->codigo ?? '—' }}</span>
                    </div>
                </div>
                @else
                <p style="color:var(--muted); font-size:0.85rem;">Sin ubicación asignada todavía.</p>
                @endif
            </div>
        </div>

        {{-- Stock y acciones --}}
        <div>
            {{-- Stock --}}
            <div class="inv-detail-card">
                <h3>Stock</h3>
                @php
                    $fisico    = $lote->cantidadFisica();
                    $disponible = $lote->cantidadDisponible();
                    $reservado = $fisico - $disponible;
                    $unidad    = $lote->producto?->unidadMedida?->abreviatura ?? '';
                @endphp
                <div style="display:flex; flex-direction:column; gap:12px;">
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:0.8rem; color:var(--muted);">Físico total</span>
                        <span style="font-weight:600;">{{ number_format($fisico, 0) }} {{ $unidad }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="font-size:0.8rem; color:var(--muted);">Reservado</span>
                        <span style="font-weight:500; color:{{ $reservado > 0 ? '#a16207' : 'var(--muted)' }};">{{ number_format($reservado, 0) }} {{ $unidad }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; border-top:1px solid var(--border); padding-top:10px;">
                        <span style="font-size:0.85rem; font-weight:500;">Disponible</span>
                        <span style="font-size:1.25rem; font-weight:700; color:{{ $disponible > 0 ? '#15803d' : '#b91c1c' }};">{{ number_format($disponible, 0) }} {{ $unidad }}</span>
                    </div>
                </div>
            </div>

            {{-- Acciones rápidas --}}
            @if($lote->estado->value === 'stored')
            <div class="inv-detail-card">
                <h3>Acciones</h3>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    {{-- Mover a producción --}}
                    <form method="POST" action="{{ route('inventario.produccion.mover', $lote->id_lote) }}"
                        onsubmit="return confirm('¿Mover lote a producción? Saldrá del inventario de almacén.')">
                        @csrf
                        <button type="submit" class="inv-btn inv-btn-outline" style="width:100%;">
                            <iconify-icon icon="lucide:factory"></iconify-icon>
                            Mover a producción
                        </button>
                    </form>

                    <a href="{{ route('inventario.expediciones.create') }}?lote={{ $lote->id_lote }}" class="inv-btn inv-btn-primary" style="width:100%; justify-content:center;">
                        <iconify-icon icon="lucide:send"></iconify-icon>
                        Preparar expedición
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
