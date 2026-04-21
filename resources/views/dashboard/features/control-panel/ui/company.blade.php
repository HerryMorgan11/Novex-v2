<div class="panel-header">
    <h1>Información de la Empresa</h1>
    <p>Detalles técnicos y administrativos de la organización actual en el tenant.</p>
</div>

<div class="info-grid">
    <div class="info-card">
        <h3>Nombre de Empresa</h3>
        <p>{{ optional(tenant())->name ?? 'N/A' }}</p>
    </div>

    <div class="info-card">
        <h3>Identificador del Tenant</h3>
        <p class="cp-company-tenant-id">{{ optional(tenant())->id ?? 'N/A' }}</p>
    </div>

    <div class="info-card">
        <h3>Slug / Ruta</h3>
        <p>{{ optional(tenant())->slug ?? 'N/A' }}</p>
    </div>

    <div class="info-card">
        <h3>Base de Datos</h3>
        <p class="cp-company-monospace">{{ optional(tenant())->db_name ?? 'N/A' }}</p>
    </div>

    <div class="info-card">
        <h3>Dominio de Acceso</h3>
        <p class="cp-company-monospace">{{ request()->getHost() }}</p>
    </div>

    <div class="info-card">
        <h3>Estado Organizacional</h3>
        <p class="cp-company-status-text">
            <span class="cp-company-status-dot"></span>
            {{ optional(tenant())->status ?? 'Activo' }}
        </p>
    </div>
</div>
