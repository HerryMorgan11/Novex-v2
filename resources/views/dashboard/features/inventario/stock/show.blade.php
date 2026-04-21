@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventario.css', 'resources/css/dashboard/features/inventario/stock.css'])
@endpush

@section('content')
<div class="inv-page-wrapper">

    @include('dashboard.features.inventario.partials.top-nav')

    <div class="inv-page-header">
        <div>
            <h1>{{ $lote->numero_lote }}</h1>
            <div class="inv-breadcrumb">
                <a href="{{ route('inventario.index') }}" class="inv-breadcrumb-link">Inventario</a>
                &rsaquo;
                <a href="{{ route('inventario.stock.index') }}" class="inv-breadcrumb-link">Stock</a>
                &rsaquo; {{ $lote->numero_lote }}
            </div>
        </div>
        <div class="inv-header-actions">
            @php $color = $lote->estado?->color() ?? 'secondary' @endphp
            <span class="badge badge-{{ $color }} inv-badge-lg">
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

    <div class="inv-detail-layout">

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
                        <span class="inv-mono">{{ $lote->producto?->sku ?? '—' }}</span>
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
                    <div class="inv-detail-item inv-detail-item-full">
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
                        <span class="inv-mono inv-ub-code">{{ $lote->ubicacion->codigoCompleto() }}</span>
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
                <p class="inv-card-muted-text">Sin ubicación asignada todavía.</p>
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
                <div class="inv-stock-panel">
                    <div class="inv-stock-row">
                        <span class="inv-stock-label">Físico total</span>
                        <span class="inv-stock-value">{{ number_format($fisico, 0) }} {{ $unidad }}</span>
                    </div>
                    <div class="inv-stock-row">
                        <span class="inv-stock-label">Reservado</span>
                        <span style="font-weight:500; color:{{ $reservado > 0 ? '#a16207' : 'var(--muted)' }};">{{ number_format($reservado, 0) }} {{ $unidad }}</span>
                    </div>
                    <div class="inv-stock-divider">
                        <span class="inv-stock-total">Disponible</span>
                        <span style="font-size:1.25rem; font-weight:700; color:{{ $disponible > 0 ? '#15803d' : '#b91c1c' }};">{{ number_format($disponible, 0) }} {{ $unidad }}</span>
                    </div>
                </div>
            </div>

            {{-- Acciones rápidas --}}
            @if($lote->estado->value === 'stored')
            <div class="inv-detail-card">
                <h3>Acciones</h3>
                <div class="inv-actions-col">
                    {{-- Mover a producción --}}
                    <form method="POST" action="{{ route('inventario.produccion.mover', $lote->id_lote) }}"
                        onsubmit="return confirm('¿Mover lote a producción? Saldrá del inventario de almacén.')">
                        @csrf
                        <button type="submit" class="inv-btn inv-btn-outline inv-btn-block">
                            <iconify-icon icon="lucide:factory"></iconify-icon>
                            Mover a producción
                        </button>
                    </form>

                    <a href="{{ route('inventario.expediciones.create') }}?lote={{ $lote->id_lote }}" class="inv-btn inv-btn-primary inv-btn-block">
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
