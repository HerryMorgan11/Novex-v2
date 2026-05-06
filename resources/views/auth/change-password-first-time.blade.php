<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear nueva contraseña - Novex</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/auth/auth.css', 'resources/js/app.js'])
</head>

<body>
    <div class="bg-shape shape-1"></div>
    <div class="bg-shape shape-2"></div>

    <div class="login-container">
        <img src="{{ asset('assets/logo/logo-novex-color.png') }}" alt="Novex Logo" class="logo">
        <h2 class="title-sing">Crea tu contraseña</h2>
        <p style="text-align:center; color: var(--text-secondary, #94a3b8); font-size: 0.875rem; margin-bottom: 1.5rem;">
            Es tu primer acceso. Por seguridad, debes establecer una contraseña personal antes de continuar.
        </p>

        @if ($errors->any())
            <div style="background:#fee2e2; border:1px solid #fca5a5; border-radius:8px; padding:0.75rem 1rem; margin-bottom:1rem; font-size:0.875rem; color:#dc2626;">
                <ul style="margin:0; padding-left:1.2rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.change-first-time.update') }}" method="POST" class="login-form">
            @csrf

            <div class="form-group">
                <label for="current_password">CONTRASEÑA PROVISIONAL</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <iconify-icon icon="iconoir:lock"></iconify-icon>
                    </span>
                    <input type="password" id="current_password" name="current_password"
                        placeholder="La contraseña que te dieron" required autocomplete="current-password">
                    <button type="button" class="toggle-password" onclick="togglePasswordField('current_password', 'icon-eye-current')">
                        <iconify-icon id="icon-eye-current" icon="iconoir:eye"></iconify-icon>
                    </button>
                </div>
                @error('current_password')
                    <span style="color:#dc2626; font-size:0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">NUEVA CONTRASEÑA</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <iconify-icon icon="iconoir:lock"></iconify-icon>
                    </span>
                    <input type="password" id="password" name="password"
                        placeholder="Mínimo 8 caracteres" required autocomplete="new-password">
                    <button type="button" class="toggle-password" onclick="togglePasswordField('password', 'icon-eye-new')">
                        <iconify-icon id="icon-eye-new" icon="iconoir:eye"></iconify-icon>
                    </button>
                </div>
                @error('password')
                    <span style="color:#dc2626; font-size:0.8rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">CONFIRMAR CONTRASEÑA</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <iconify-icon icon="iconoir:lock"></iconify-icon>
                    </span>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                        placeholder="Repite la nueva contraseña" required autocomplete="new-password">
                    <button type="button" class="toggle-password" onclick="togglePasswordField('password_confirmation', 'icon-eye-confirm')">
                        <iconify-icon id="icon-eye-confirm" icon="iconoir:eye"></iconify-icon>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-primary">Establecer contraseña y entrar</button>
        </form>
    </div>

    <script>
        function togglePasswordField(fieldId, iconId) {
            const input = document.getElementById(fieldId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('icon', 'iconoir:eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('icon', 'iconoir:eye');
            }
        }
    </script>
</body>
</html>
