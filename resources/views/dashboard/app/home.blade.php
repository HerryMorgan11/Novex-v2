@extends('dashboard.app.dashboard')

@push('styles')
@vite(['resources/css/dashboard/features/dashboard.css'])
@endpush

@section('content')

{{-- Datos de gráficas embebidos como JSON para el JS ─────────────────────── --}}
@if(!($showModal ?? false) && !empty($chartData))
<script id="dashboard-chart-data" type="application/json">
    {!! json_encode($chartData, JSON_HEX_TAG | JSON_HEX_AMP) !!}
</script>
@endif

<div class="dash-app-content-wrapper">

    {{-- ── Header ─────────────────────────────────────────────────────────── --}}
    <div class="db-header">
        <div class="db-header-left">
            <h1>Dashboard</h1>
            <div class="db-subtitle">
                <iconify-icon icon="lucide:layout-grid" width="13"></iconify-icon>
                Bienvenido de nuevo, <strong>{{ auth()->user()->name }}</strong>
                &nbsp;—&nbsp;{{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
            </div>
        </div>

        @if(!($showModal ?? false))
        <div class="db-period-tabs" role="group" aria-label="Período de datos">
            <button data-period="today"  class="{{ $period === 'today' ? 'active' : '' }}">Hoy</button>
            <button data-period="week"   class="{{ $period === 'week'  ? 'active' : '' }}">Esta semana</button>
            <button data-period="month"  class="{{ $period === 'month' ? 'active' : '' }}">Este mes</button>
        </div>
        @endif
    </div>

    {{-- ── Sin tenant: modal placeholder ─────────────────────────────────── --}}
    @if($showModal ?? false)
    <div class="dash-app-no-tenant">
        <iconify-icon icon="lucide:building-2" class="dash-app-no-tenant-icon"></iconify-icon>
        <div>
            <p class="dash-app-no-tenant-title">No tienes una empresa configurada</p>
            <p class="dash-app-no-tenant-subtitle">Crea tu empresa para comenzar a usar Novex ERP</p>
        </div>
    </div>
    @else

    {{-- ── KPI Cards ───────────────────────────────────────────────────────── --}}
    <div class="db-kpi-grid">
        <div class="db-kpi-card">
            <div class="db-kpi-icon blue"><iconify-icon icon="lucide:package" width="18"></iconify-icon></div>
            <div class="db-kpi-value" id="kpi-productos-activos">{{ $metrics['productos_activos'] ?? 0 }}</div>
            <div class="db-kpi-label">Productos activos</div>
        </div>
        <div class="db-kpi-card">
            <div class="db-kpi-icon amber"><iconify-icon icon="lucide:alert-circle" width="18"></iconify-icon></div>
            <div class="db-kpi-value" id="kpi-productos-borrador">{{ $metrics['productos_borrador'] ?? 0 }}</div>
            <div class="db-kpi-label">Pendientes validación</div>
        </div>
        <div class="db-kpi-card">
            <div class="db-kpi-icon teal"><iconify-icon icon="lucide:boxes" width="18"></iconify-icon></div>
            <div class="db-kpi-value" id="kpi-stock-total">
                {{ number_format($metrics['stock_total'] ?? 0, 0, ',', '.') }}
            </div>
            <div class="db-kpi-label">Unidades en stock</div>
        </div>
        <div class="db-kpi-card">
            <div class="db-kpi-icon rose"><iconify-icon icon="lucide:truck" width="18"></iconify-icon></div>
            <div class="db-kpi-value" id="kpi-transportes-pendientes">{{ $metrics['transportes_pendientes'] ?? 0 }}</div>
            <div class="db-kpi-label">Transportes pendientes</div>
        </div>
        <div class="db-kpi-card">
            <div class="db-kpi-icon violet"><iconify-icon icon="lucide:send" width="18"></iconify-icon></div>
            <div class="db-kpi-value" id="kpi-expediciones-activas">{{ $metrics['expediciones_activas'] ?? 0 }}</div>
            <div class="db-kpi-label">Expediciones activas</div>
        </div>
        <div class="db-kpi-card">
            <div class="db-kpi-icon green"><iconify-icon icon="lucide:warehouse" width="18"></iconify-icon></div>
            <div class="db-kpi-value" id="kpi-lotes-almacenados">{{ $metrics['lotes_almacenados'] ?? 0 }}</div>
            <div class="db-kpi-label">Lotes almacenados</div>
        </div>
        <div class="db-kpi-card">
            <div class="db-kpi-icon sky"><iconify-icon icon="lucide:bell" width="18"></iconify-icon></div>
            <div class="db-kpi-value" id="kpi-recordatorios-activos">{{ $metrics['recordatorios_activos'] ?? 0 }}</div>
            <div class="db-kpi-label">Recordatorios activos</div>
        </div>
        <div class="db-kpi-card">
            <div class="db-kpi-icon slate"><iconify-icon icon="lucide:sticky-note" width="18"></iconify-icon></div>
            <div class="db-kpi-value" id="kpi-notas-total">{{ $metrics['notas_total'] ?? 0 }}</div>
            <div class="db-kpi-label">Notas</div>
        </div>
    </div>

    {{-- ── Gráficas ────────────────────────────────────────────────────────── --}}
    <p class="db-charts-title">Análisis de datos</p>

    <div class="db-charts-grid">

        {{-- Actividad de movimientos (línea) --}}
        <div class="db-chart-card wide">
            <div class="db-chart-card-header">
                <div class="db-chart-card-title">
                    <iconify-icon icon="lucide:activity" width="15"></iconify-icon>
                    Actividad de movimientos de inventario
                </div>
            </div>
            <div class="db-chart-canvas-wrap">
                <canvas id="chartMovimientos"></canvas>
            </div>
        </div>

        {{-- Transportes por estado (donut) --}}
        <div class="db-chart-card">
            <div class="db-chart-card-header">
                <div class="db-chart-card-title">
                    <iconify-icon icon="lucide:truck" width="15"></iconify-icon>
                    Transportes por estado
                </div>
            </div>
            <div class="db-chart-canvas-wrap">
                <canvas id="chartTransportes"></canvas>
            </div>
        </div>

        {{-- Expediciones por estado (barras) --}}
        <div class="db-chart-card">
            <div class="db-chart-card-header">
                <div class="db-chart-card-title">
                    <iconify-icon icon="lucide:send" width="15"></iconify-icon>
                    Expediciones por estado
                </div>
            </div>
            <div class="db-chart-canvas-wrap">
                <canvas id="chartExpediciones"></canvas>
            </div>
        </div>


    </div>

    @endif {{-- end !showModal --}}

</div>

@push('scripts')
@vite(['resources/js/dashboard/features/dashboard.js'])
@endpush

@endsection
