@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/inventario.css'])
@endpush

@section('content')
<div style="padding: 20px 0;">

    {{-- Header --}}
    <div class="inv-page-header">
        <div>
            <h1>Inventario</h1>
            <div class="inv-breadcrumb">
                <iconify-icon icon="lucide:layout-grid" width="12"></iconify-icon>
                Dashboard &rsaquo; Inventario
            </div>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
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
        <div class="inv-stat-card" style="border-color: #fde68a;">
            <div class="stat-icon" style="color:#a16207"><iconify-icon icon="lucide:alert-circle"></iconify-icon></div>
            <div class="stat-value" style="color:#a16207">{{ $stats['productos_borrador'] }}</div>
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
    <div style="display:grid; grid-template-columns: repeat(auto-fill, minmax(200px,1fr)); gap:12px; margin-bottom:28px;">
        <a href="{{ route('inventario.stock.index') }}" class="transport-card" style="gap:10px;">
            <div style="font-size:2rem; color:var(--muted-2);"><iconify-icon icon="lucide:boxes"></iconify-icon></div>
            <div style="font-weight:600; color:var(--fg);">Stock</div>
            <div style="font-size:0.8rem; color:var(--muted);">Ver inventario completo</div>
        </a>
        <a href="{{ route('inventario.transportes.index') }}" class="transport-card" style="gap:10px;">
            <div style="font-size:2rem; color:var(--muted-2);"><iconify-icon icon="lucide:truck"></iconify-icon></div>
            <div style="font-weight:600; color:var(--fg);">Transportes</div>
            <div style="font-size:0.8rem; color:var(--muted);">Entradas de mercancía</div>
        </a>
        <a href="{{ route('inventario.produccion.index') }}" class="transport-card" style="gap:10px;">
            <div style="font-size:2rem; color:var(--muted-2);"><iconify-icon icon="lucide:factory"></iconify-icon></div>
            <div style="font-weight:600; color:var(--fg);">Producción</div>
            <div style="font-size:0.8rem; color:var(--muted);">Mover a producción</div>
        </a>
        <a href="{{ route('inventario.expediciones.index') }}" class="transport-card" style="gap:10px;">
            <div style="font-size:2rem; color:var(--muted-2);"><iconify-icon icon="lucide:send"></iconify-icon></div>
            <div style="font-weight:600; color:var(--fg);">Reparto</div>
            <div style="font-size:0.8rem; color:var(--muted);">Expediciones de salida</div>
        </a>
        <a href="{{ route('inventario.almacenes.index') }}" class="transport-card" style="gap:10px;">
            <div style="font-size:2rem; color:var(--muted-2);"><iconify-icon icon="lucide:warehouse"></iconify-icon></div>
            <div style="font-weight:600; color:var(--fg);">Almacenes</div>
            <div style="font-size:0.8rem; color:var(--muted);">Estructura física</div>
        </a>
    </div>

    {{-- Dos columnas: transportes y expediciones recientes --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">

        {{-- Transportes recientes --}}
        <div class="inv-detail-card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                <h3 style="margin-bottom:0; border-bottom:none; padding-bottom:0;">Transportes recientes</h3>
                <a href="{{ route('inventario.transportes.index') }}" class="inv-btn inv-btn-ghost" style="font-size:0.8rem; padding:4px 10px;">Ver todos</a>
            </div>
            @forelse($transportesRecientes as $t)
            <a href="{{ route('inventario.transportes.show', $t->id_recepcion) }}" style="display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid var(--border); text-decoration:none; color:inherit;">
                <div>
                    <div style="font-size:0.85rem; font-weight:600;">{{ $t->codigo_recepcion }}</div>
                    <div style="font-size:0.75rem; color:var(--muted);">{{ $t->origen ?? '—' }} → {{ $t->destino ?? '—' }}</div>
                </div>
                <div style="display:flex; flex-direction:column; align-items:flex-end; gap:4px;">
                    @php $color = $t->estado?->color() ?? 'secondary' @endphp
                    <span class="badge badge-{{ $color }}">{{ $t->estado?->label() ?? $t->estado }}</span>
                    <span style="font-size:0.72rem; color:var(--muted-2);">{{ $t->lineas->count() }} líneas</span>
                </div>
            </a>
            @empty
            <p style="font-size:0.85rem; color:var(--muted); text-align:center; padding:20px 0;">Sin transportes recientes</p>
            @endforelse
        </div>

        {{-- Expediciones recientes --}}
        <div class="inv-detail-card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:14px;">
                <h3 style="margin-bottom:0; border-bottom:none; padding-bottom:0;">Expediciones recientes</h3>
                <a href="{{ route('inventario.expediciones.index') }}" class="inv-btn inv-btn-ghost" style="font-size:0.8rem; padding:4px 10px;">Ver todas</a>
            </div>
            @forelse($expedicionesRecientes as $e)
            <a href="{{ route('inventario.expediciones.show', $e->id_expedicion) }}" style="display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-bottom:1px solid var(--border); text-decoration:none; color:inherit;">
                <div>
                    <div style="font-size:0.85rem; font-weight:600;">{{ $e->referencia_expedicion }}</div>
                    <div style="font-size:0.75rem; color:var(--muted);">{{ $e->destino ?? '—' }}</div>
                </div>
                <div style="display:flex; flex-direction:column; align-items:flex-end; gap:4px;">
                    @php $color = $e->estado?->color() ?? 'secondary' @endphp
                    <span class="badge badge-{{ $color }}">{{ $e->estado?->label() ?? $e->estado }}</span>
                    <span style="font-size:0.72rem; color:var(--muted-2);">{{ $e->lineas->count() }} líneas</span>
                </div>
            </a>
            @empty
            <p style="font-size:0.85rem; color:var(--muted); text-align:center; padding:20px 0;">Sin expediciones recientes</p>
            @endforelse
        </div>
    </div>

    {{-- Lotes con incidencias --}}
    @if($lotesBloqueados->isNotEmpty())
    <div class="inv-alert inv-alert-warning" style="margin-bottom:16px;">
        <iconify-icon icon="lucide:alert-triangle"></iconify-icon>
        <div>
            <strong>{{ $lotesBloqueados->count() }} lotes con incidencia o bloqueados</strong> requieren atención.
        </div>
    </div>
    @endif

</div>
@endsection
