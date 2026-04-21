@push('styles')
@vite(['resources/css/dashboard/features/settings/settings.css'])
@endpush
@php($user = auth()->user())

<div class="settings-section">
    <h2>Mi Perfil</h2>
    <p class="sett-section-intro">
        Actualiza tu nombre y correo electrónico. Estos datos se usan en toda la plataforma.
    </p>

    @if (session('success'))
        <div class="settings-alert settings-alert-success" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->updateProfileInformation->any())
        <div class="settings-alert settings-alert-error" role="alert">
            <ul class="sett-error-list">
                @foreach ($errors->updateProfileInformation->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('settings.profile.update') }}" class="settings-section-content">
        @csrf
        @method('PUT')

        <div class="settings-field">
            <label for="settings-name">Nombre Completo</label>
            <input id="settings-name" type="text" name="name" required maxlength="255"
                   value="{{ old('name', $user->name) }}" class="settings-input">
        </div>

        <div class="settings-field">
            <label for="settings-email">Correo Electrónico</label>
            <input id="settings-email" type="email" name="email" required maxlength="255"
                   value="{{ old('email', $user->email) }}" class="settings-input">
        </div>

        <div class="sett-submit-wrap">
            <button type="submit" class="settings-btn settings-btn-primary">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>

<div class="settings-section sett-section-no-margin">
    <h2>Información Adicional</h2>
    <p class="sett-section-intro">
        Datos complementarios usados para facturación y contacto (pendiente de implementar en backend).
    </p>

    <div class="settings-section-content">
        <div class="settings-field">
            <label>Teléfono Opcional</label>
            <p>{{ $user->phone ?? 'No proporcionado' }}</p>
        </div>

        <div class="settings-field">
            <label>DNI / Identidad Civil</label>
            <p>{{ $user->dni ?? 'No proporcionado' }}</p>
        </div>
    </div>
</div>
