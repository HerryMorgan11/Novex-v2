<div class="panel-header">
    <h1>Información de la Empresa</h1>
</div>

<div class="info-grid">
    <div class="info-card">
        <h3>Nombre de Empresa</h3>
        <p>{{ optional(tenant())->name ?? 'N/A' }}</p>
    </div>

    <div class="info-card">
        <h3>Identificador del Tenant</h3>
        <p>{{ optional(tenant())->id ?? 'N/A' }}</p>
    </div>

    <div class="info-card">
        <h3>Slug</h3>
        <p>{{ optional(tenant())->slug ?? 'N/A' }}</p>
    </div>

    <div class="info-card">
        <h3>Base de Datos</h3>
        <p style="font-family: monospace; font-size: 0.875rem;">{{ optional(tenant())->db_name ?? 'N/A' }}</p>
    </div>

    <div class="info-card">
        <h3>Dominio</h3>
        <p style="font-family: monospace; font-size: 0.875rem;">{{ request()->getHost() }}</p>
    </div>

    <div class="info-card">
        <h3>Estado</h3>
        <p>{{ optional(tenant())->status ?? 'N/A' }}</p>
    </div>
</div>
