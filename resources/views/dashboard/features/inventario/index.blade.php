@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventario/general-inventario.css', 'resources/css/dashboard/features/inventario/inventario.css'])
@endpush

@section('content')
<div class="inv-page-wrapper">

    @include('dashboard.features.inventario.partials.top-nav')

    {{-- Header --}}
    <div class="inv-page-header">
        <div>
            <h1>Inventario</h1>
            <div class="inv-breadcrumb">
                <iconify-icon icon="lucide:layout-grid" width="12"></iconify-icon>
                Dashboard &rsaquo; Inventario
            </div>
        </div>
        <div class="inv-header-actions">
            <a href="{{ route('inventario.transportes.index') }}" class="inv-btn inv-btn-outline">
                <iconify-icon icon="lucide:truck"></iconify-icon>
                Transportes
            </a>
            <a href="{{ route('inventario.expediciones.create') }}" class="inv-btn inv-btn-primary">
                <iconify-icon icon="lucide:send"></iconify-icon>
                Nueva Expedición
            </a>
        </div>
    </div>

    {{-- Alertas de sesión --}}
    @if(session('success'))
    <div class="inv-alert inv-alert-success">
        <iconify-icon icon="lucide:check-circle"></iconify-icon>
        {{ session('success') }}
    </div>
    @endif

    {{-- Stats --}}
    <div class="inv-stats-grid">
        <div class="inv-stat-card">
            <div class="stat-icon"><iconify-icon icon="lucide:package"></iconify-icon></div>
            <div class="stat-value">{{ $stats['productos_activos'] }}</div>
            <div class="stat-label">Productos activos</div>
        </div>
        @if($stats['productos_borrador'] > 0)
        <div class="inv-stat-card inv-stat-card-draft">
            <div class="stat-icon inv-icon-draft"><iconify-icon icon="lucide:alert-circle"></iconify-icon></div>
            <div class="stat-value inv-value-draft">{{ $stats['productos_borrador'] }}</div>
            <div class="stat-label">Pendientes validación</div>
        </div>
        @endif
        <div class="inv-stat-card">
            <div class="stat-icon"><iconify-icon icon="lucide:warehouse"></iconify-icon></div>
            <div class="stat-value">{{ $stats['lotes_almacenados'] }}</div>
            <div class="stat-label">Lotes almacenados</div>
        </div>
        <div class="inv-stat-card">
            <div class="stat-icon"><iconify-icon icon="lucide:truck"></iconify-icon></div>
            <div class="stat-value">{{ $stats['transportes_pendientes'] }}</div>
            <div class="stat-label">Transportes pendientes</div>
        </div>
        <div class="inv-stat-card">
            <div class="stat-icon"><iconify-icon icon="lucide:send"></iconify-icon></div>
            <div class="stat-value">{{ $stats['lotes_en_transito'] }}</div>
            <div class="stat-label">Lotes en tránsito</div>
        </div>
        <div class="inv-stat-card">
            <div class="stat-icon"><iconify-icon icon="lucide:clipboard-list"></iconify-icon></div>
            <div class="stat-value">{{ $stats['expediciones_activas'] }}</div>
            <div class="stat-label">Expediciones activas</div>
        </div>
    </div>

    {{-- Accesos rápidos --}}
    <div class="inv-quick-access-grid">
        <a href="{{ route('inventario.stock.index') }}" class="transport-card inv-quick-card">
            <div class="inv-quick-card-icon"><iconify-icon icon="lucide:boxes"></iconify-icon></div>
            <div class="inv-quick-card-title">Stock</div>
            <div class="inv-quick-card-desc">Ver inventario completo</div>
        </a>
        <a href="{{ route('inventario.transportes.index') }}" class="transport-card inv-quick-card">
            <div class="inv-quick-card-icon"><iconify-icon icon="lucide:truck"></iconify-icon></div>
            <div class="inv-quick-card-title">Transportes</div>
            <div class="inv-quick-card-desc">Entradas de mercancía</div>
        </a>
        <a href="{{ route('inventario.produccion.index') }}" class="transport-card inv-quick-card">
            <div class="inv-quick-card-icon"><iconify-icon icon="lucide:factory"></iconify-icon></div>
            <div class="inv-quick-card-title">Producción</div>
            <div class="inv-quick-card-desc">Mover a producción</div>
        </a>
        <a href="{{ route('inventario.expediciones.index') }}" class="transport-card inv-quick-card">
            <div class="inv-quick-card-icon"><iconify-icon icon="lucide:send"></iconify-icon></div>
            <div class="inv-quick-card-title">Reparto</div>
            <div class="inv-quick-card-desc">Expediciones de salida</div>
        </a>
        <a href="{{ route('inventario.almacenes.index') }}" class="transport-card inv-quick-card">
            <div class="inv-quick-card-icon"><iconify-icon icon="lucide:warehouse"></iconify-icon></div>
            <div class="inv-quick-card-title">Almacenes</div>
            <div class="inv-quick-card-desc">Estructura física</div>
        </a>
    </div>

    {{-- Dos columnas: transportes y expediciones recientes --}}
    <div class="inv-split-grid">

        {{-- Transportes recientes --}}
        <div class="inv-detail-card">
            <div class="inv-card-header-row">
                <h3 class="inv-card-title-inline">Transportes recientes</h3>
                <a href="{{ route('inventario.transportes.index') }}" class="inv-btn inv-btn-ghost inv-btn-sm">Ver todos</a>
            </div>
            @forelse($transportesRecientes as $t)
            <a href="{{ route('inventario.transportes.show', $t->id_recepcion) }}" class="inv-list-item">
                <div>
                    <div class="inv-list-item-title">{{ $t->codigo_recepcion }}</div>
                    <div class="inv-list-item-sub">{{ $t->origen ?? '—' }} → {{ $t->destino ?? '—' }}</div>
                </div>
                <div class="inv-list-item-end">
                    @php $color = $t->estado?->color() ?? 'secondary' @endphp
                    <span class="badge badge-{{ $color }}">{{ $t->estado?->label() ?? $t->estado }}</span>
                    <span class="inv-list-item-meta">{{ $t->lineas->count() }} líneas</span>
                </div>
            </a>
            @empty
            <p class="inv-list-empty">Sin transportes recientes</p>
            @endforelse
        </div>

        {{-- Expediciones recientes --}}
        <div class="inv-detail-card">
            <div class="inv-card-header-row">
                <h3 class="inv-card-title-inline">Expediciones recientes</h3>
                <a href="{{ route('inventario.expediciones.index') }}" class="inv-btn inv-btn-ghost inv-btn-sm">Ver todas</a>
            </div>
            @forelse($expedicionesRecientes as $e)
            <a href="{{ route('inventario.expediciones.show', $e->id_expedicion) }}" class="inv-list-item">
                <div>
                    <div class="inv-list-item-title">{{ $e->referencia_expedicion }}</div>
                    <div class="inv-list-item-sub">{{ $e->destino ?? '—' }}</div>
                </div>
                <div class="inv-list-item-end">
                    @php $color = $e->estado?->color() ?? 'secondary' @endphp
                    <span class="badge badge-{{ $color }}">{{ $e->estado?->label() ?? $e->estado }}</span>
                    <span class="inv-list-item-meta">{{ $e->lineas->count() }} líneas</span>
                </div>
            </a>
            @empty
            <p class="inv-list-empty">Sin expediciones recientes</p>
            @endforelse
        </div>
    </div>

    {{-- Lotes con incidencias --}}
    @if($lotesBloqueados->isNotEmpty())
    <div class="inv-alert inv-alert-warning">
        <iconify-icon icon="lucide:alert-triangle"></iconify-icon>
        <div>
            <strong>{{ $lotesBloqueados->count() }} lotes con incidencia o bloqueados</strong> requieren atención.
        </div>
    </div>
    @endif

</div>
@endsection
