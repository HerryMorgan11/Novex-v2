<div class="settings-section">
    <h2>Mi Perfil</h2>
    <p style="color: var(--muted); padding-bottom: 1.5rem; font-size: 0.95rem;">
        Gestiona tu información personal y verifica el estado de tu cuenta global.
    </p>

    <div class="settings-section-content">
        <div class="settings-field">
            <label>Nombre Completo</label>
            <p>{{ auth()->user()->name ?? 'Sin nombre' }}</p>
        </div>

        <div class="settings-field">
            <label>Correo Electrónico</label>
            <p>{{ auth()->user()->email }}</p>
        </div>

        <div style="padding-top: 0.5rem;">
            <button class="settings-btn settings-btn-primary">
                Editar Perfil
            </button>
        </div>
    </div>
</div>

<div class="settings-section" style="margin-bottom: 0;">
    <h2>Información Adicional</h2>
    <p style="color: var(--muted); padding-bottom: 1.5rem; font-size: 0.95rem;">
        Completa tu documentación para desbloquear ciertas características facturables.
    </p>

    <div class="settings-section-content">
        <div class="settings-field">
            <label>Teléfono Opcional</label>
            <p>{{ auth()->user()->phone ?? 'No proporcionado' }}</p>
            <small style="color: var(--muted-2); margin-top: 0.4rem; display: block;">
                Agrega el teléfono, será útil para recuperar accesos rápidamente o para contactarte de urgencias.
            </small>
        </div>

        <div class="settings-field">
            <label>DNI / Identidad Civil</label>
            <p>{{ auth()->user()->dni ?? 'No proporcionado' }}</p>
            <small style="color: var(--muted-2); margin-top: 0.4rem; display: block;">
                El documento de identidad es requerido y se utiliza principalmente por propósitos legales de la facturación del servicio y pasarela de pago local.
            </small>
        </div>

        <div style="padding-top: 0.5rem;">
            <button class="settings-btn settings-btn-secondary">
                Editar Información Adicional
            </button>
        </div>
    </div>
</div>
