@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventario/general-inventario.css', 'resources/css/dashboard/features/inventario/stock.css'])
@endpush

@section('content')
<div class="inv-page-wrapper">

    @include('dashboard.features.inventario.partials.top-nav')

    <div class="inv-page-header">
        <div>
            <h1>Stock / Inventario</h1>
            <div class="inv-breadcrumb">
                <a href="{{ route('inventario.index') }}" class="inv-breadcrumb-link">Inventario</a>
                &rsaquo; Stock
            </div>
        </div>
        <a href="{{ route('inventario.expediciones.create') }}" class="inv-btn inv-btn-primary">
            <iconify-icon icon="lucide:send"></iconify-icon>
            Nueva expedición
        </a>
    </div>

    @if(session('success'))
    <div class="inv-alert inv-alert-success"><iconify-icon icon="lucide:check-circle"></iconify-icon> {{ session('success') }}</div>
    @endif

    {{-- Filtros --}}
    <form method="GET" class="inv-filters">
        <div class="inv-search">
            <iconify-icon icon="lucide:search"></iconify-icon>
            <input type="text" name="search" placeholder="Buscar por lote, SKU o nombre..."
                value="{{ request('search') }}">
        </div>
        <select name="estado" class="inv-select" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            @foreach($estados as $estado)
            <option value="{{ $estado->value }}" {{ request('estado') === $estado->value ? 'selected' : '' }}>
                {{ $estado->label() }}
            </option>
            @endforeach
        </select>
        <select name="categoria" class="inv-select" onchange="this.form.submit()">
            <option value="">Todas las categorías</option>
            @foreach($categorias as $cat)
            <option value="{{ $cat->id_categoria }}" {{ request('categoria') == $cat->id_categoria ? 'selected' : '' }}>
                {{ $cat->nombre }}
            </option>
            @endforeach
        </select>
        <button type="submit" class="inv-btn inv-btn-outline">
            <iconify-icon icon="lucide:filter"></iconify-icon>
            Filtrar
        </button>
        @if(request()->hasAny(['search','estado','categoria']))
        <a href="{{ route('inventario.stock.index') }}" class="inv-btn inv-btn-ghost">Limpiar</a>
        @endif
    </form>

    {{-- Tabla --}}
    @if($lotes->isEmpty())
    <div class="inv-empty">
        <iconify-icon icon="lucide:boxes"></iconify-icon>
        <p>No se encontraron lotes con los filtros actuales.</p>
    </div>
    @else
    <div class="inv-table-wrapper">
        <table class="inv-table">
            <thead>
                <tr>
                    <th>Lote</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Ubicación</th>
                    <th>Stock</th>
                    <th>Estado</th>
                    <th>Recepción</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($lotes as $lote)
                <tr>
                    <td class="mono">{{ $lote->numero_lote }}</td>
                    <td>
                        <div class="inv-text-bold">{{ $lote->producto?->nombre ?? '—' }}</div>
                        <div class="inv-text-sm-muted">{{ $lote->producto?->sku ?? '' }}</div>
                    </td>
                    <td class="inv-td-muted">
                        {{ $lote->producto?->categoria?->nombre ?? '—' }}
                    </td>
                    <td class="inv-td-sm">
                        @if($lote->ubicacion)
                        <span class="inv-location-row">
                            <iconify-icon icon="lucide:map-pin" width="12"></iconify-icon>
                            {{ $lote->ubicacion->codigoCompleto() }}
                        </span>
                        @else
                        <span class="inv-location-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $fisico = $lote->cantidadFisica();
                            $disp   = $lote->cantidadDisponible();
                            $unidad = $lote->producto?->unidadMedida?->abreviatura ?? '';
                        @endphp
                        <div class="inv-stock-main">{{ number_format($disp, 0) }} <span class="inv-stock-unit">{{ $unidad }}</span></div>
                        @if($fisico !== $disp)
                        <div class="inv-stock-sub">Físico: {{ number_format($fisico, 0) }}</div>
                        @endif
                    </td>
                    <td>
                        @php $color = $lote->estado?->color() ?? 'secondary' @endphp
                        <span class="badge badge-{{ $color }}">{{ $lote->estado?->label() ?? $lote->estado }}</span>
                    </td>
                    <td class="inv-td-muted">
                        {{ $lote->created_at->format('d/m/Y') }}
                    </td>
                    <td>
                        <div class="inv-inline-actions">
                            <a href="{{ route('inventario.stock.show', $lote->id_lote) }}" class="inv-btn inv-btn-ghost inv-btn-icon">
                                <iconify-icon icon="lucide:eye" width="13"></iconify-icon>
                            </a>
                            <a href="{{ route('inventario.trazabilidad.historial', $lote->id_lote) }}" class="inv-btn inv-btn-ghost inv-btn-icon" title="Trazabilidad">
                                <iconify-icon icon="lucide:list-tree" width="13"></iconify-icon>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="inv-pagination">
        {{ $lotes->links() }}
    </div>
    @endif

</div>
@endsection
