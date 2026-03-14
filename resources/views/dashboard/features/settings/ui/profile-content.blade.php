<div class="settings-section">
    <h2>Mi Perfil</h2>

    <div class="settings-field">
        <label>Nombre</label>
        <p>{{ auth()->user()->name ?? 'Sin nombre' }}</p>
    </div>

    <div class="settings-field">
        <label>Email</label>
        <p>{{ auth()->user()->email }}</p>
    </div>

    <div style="padding-top: 0.5rem;">
        <button class="settings-btn settings-btn-primary">
            Editar Perfil
        </button>
    </div>
</div>
