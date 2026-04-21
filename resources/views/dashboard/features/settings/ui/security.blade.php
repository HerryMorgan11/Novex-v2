@push('styles')
@vite(['resources/css/dashboard/features/settings/settings.css'])
@endpush
<div class="settings-section">
    <p class="sett-section-intro">
        Revisa los aspectos cruciales para mantener tus sesiones de forma privada y tu cuenta robusta ante amenazas.
    </p>

    @if (session('success'))
        <div class="settings-alert settings-alert-success" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->updatePassword->any())
        <div class="settings-alert settings-alert-error" role="alert">
            <ul class="sett-error-list">
                @foreach ($errors->updatePassword->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="settings-section-content">
        <div class="settings-security-item">
            <h3>Cambiar Contraseña</h3>
            <p>Es una buena práctica actualizar periódicamente la clave para una óptima protección de la plataforma.</p>

            <form method="POST" action="{{ route('settings.password.update') }}"
                  class="sett-password-form">
                @csrf
                @method('PUT')

                <input type="password" name="current_password" placeholder="Contraseña actual"
                       class="settings-input" required autocomplete="current-password">
                <input type="password" name="password" placeholder="Nueva contraseña"
                       class="settings-input" required autocomplete="new-password">
                <input type="password" name="password_confirmation" placeholder="Confirmar contraseña"
                       class="settings-input" required autocomplete="new-password">

                <div class="sett-form-actions">
                    <button type="submit" class="settings-btn settings-btn-primary">
                        <iconify-icon icon="lucide:key"></iconify-icon>
                        Actualizar Contraseña
                    </button>
                </div>
            </form>
        </div>

        <div class="settings-security-item">
            <h3 class="sett-danger-heading">Delegar cuenta al Administrador Supremo</h3>
            <p>Si envías esta solicitud el super administrador de tu sistema o de Novex podrá configurar los elementos base, revisar tu registro de actividades y corregir errores por ti de manera temporal si así lo solicitaste. Cuidado con quien cedes este permiso.</p>

            <div class="sett-danger-actions">
                <button type="button" class="settings-btn settings-btn-secondary" disabled
                        title="Funcionalidad pendiente de implementar">
                    <iconify-icon icon="lucide:shield-alert"></iconify-icon>
                    Solicitar Intervención Activa
                </button>
            </div>
        </div>
    </div>
</div>
