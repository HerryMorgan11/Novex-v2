@push('styles')
@vite(['resources/css/dashboard/features/settings/settings.css'])
@endpush
@php($user = auth()->user())

<div class="settings-section">
    <h2>Mi Perfil</h2>
    <p class="sett-section-intro">
        Actualiza tus datos personales. Estos datos se usan en toda la plataforma.
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

        {{-- Datos de acceso --}}
        <p class="sett-group-label">Datos de acceso</p>

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

        {{-- Información adicional --}}
        <p class="sett-group-label sett-group-label--spaced">Información adicional</p>

        <div class="settings-field">
            <label for="settings-phone">Teléfono
                <span class="sett-field-optional">Opcional</span>
            </label>
            <input id="settings-phone" type="tel" name="phone" maxlength="20"
                   placeholder="+34 600 000 000"
                   value="{{ old('phone', $user->phone) }}" class="settings-input">
        </div>

        <div class="settings-field">
            <label for="settings-dni">DNI / Identidad Civil
                <span class="sett-field-optional">Opcional</span>
            </label>
            <input id="settings-dni" type="text" name="dni" maxlength="15"
                   placeholder="12345678A"
                   value="{{ old('dni', $user->dni) }}" class="settings-input">
        </div>

        <div class="sett-submit-wrap">
            <button type="submit" class="settings-btn settings-btn-primary">
                Guardar Cambios
            </button>
        </div>
    </form>
</div>
