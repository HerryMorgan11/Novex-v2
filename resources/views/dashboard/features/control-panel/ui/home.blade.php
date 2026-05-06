<div class="panel-header">
    <h1>Panel de Control</h1>
    <p>Resumen general de la actividad y estado de tu empresa.</p>
</div>

@php
    $kpis = $kpis ?? [];
    $tenantCreatedAt = $kpis['tenant_created_at'] ?? null;
    $tenantStatus = $kpis['tenant_status'] ?? 'unknown';
@endphp

<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-label">Total de Usuarios</div>
        <div class="kpi-value">{{ $kpis['total_users'] ?? 0 }}</div>
        <div class="kpi-subtext cp-home-kpi-positive">
            ↑ +{{ $kpis['new_users_this_month'] ?? 0 }} este mes
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Estado del Tenant</div>
        <div class="kpi-value cp-home-kpi-capitalize">{{ $tenantStatus }}</div>
        <div class="kpi-subtext">
            @if ($tenantCreatedAt)
                Desde {{ $tenantCreatedAt->format('d/m/Y') }}
            @else
                Sin datos
            @endif
        </div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Plan Actual</div>
        <div class="kpi-value">Free</div>
        <div class="kpi-subtext cp-home-kpi-accent">Plan por defecto</div>
    </div>

    <div class="kpi-card">
        <div class="kpi-label">Antigüedad</div>
        <div class="kpi-value">
            {{ $tenantCreatedAt ? $tenantCreatedAt->diffForHumans(null, true) : '—' }}
        </div>
        <div class="kpi-subtext">desde la creación de la empresa</div>
    </div>
</div>

