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
        <p style="font-family: var(--font); color: var(--accent);">{{ optional(tenant())->id ?? 'N/A' }}</p>
    </div>

    <div class="info-card">
        <h3>Slug / Ruta</h3>
        <p>{{ optional(tenant())->slug ?? 'N/A' }}</p>
    </div>

    <div class="info-card">
        <h3>Base de Datos</h3>
        <p style="font-family: monospace; font-size: 0.9rem; color: var(--muted);">{{ optional(tenant())->db_name ?? 'N/A' }}</p>
    </div>

    <div class="info-card">
        <h3>Dominio de Acceso</h3>
        <p style="font-family: monospace; font-size: 0.9rem; color: var(--muted);">{{ request()->getHost() }}</p>
    </div>

    <div class="info-card">
        <h3>Estado Organizacional</h3>
        <p style="display: flex; align-items: center; gap: 0.5rem; color: #10b981;">
            <span style="display: block; width: 8px; height: 8px; border-radius: 50%; background: #10b981;"></span>
            {{ optional(tenant())->status ?? 'Activo' }}
        </p>
    </div>
</div>
