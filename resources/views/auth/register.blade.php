<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear cuenta - Novex</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/auth/register.css', 'resources/js/app.js'])
</head>

<body>
    <div class="auth-container">
        <!-- Left Column - Form Section -->
        <section class="auth-form-section">
            <div class="form-wrapper">
                <!-- Logo -->
                <div class="form-header">
                    <img src="{{ asset('assets/logo/logo-novex-color.png') }}" alt="Novex Logo" class="header-logo">
                </div>

                <!-- Heading -->
                <div class="auth-heading">
                    <h1>Crear cuenta</h1>
                    <p>Únete a Novex y comienza a colaborar con tu equipo</p>
                </div>

                <!-- Registration Form -->
                <form action="{{ route('register') }}" method="POST" class="auth-form" id="registerForm">
                    @csrf

                    <!-- Full Name Field -->
                    <div class="form-group">
                        <label for="name">Nombre completo</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <iconify-icon icon="iconoir:user"></iconify-icon>
                            </span>
                            <input type="text" id="name" name="name" placeholder="Juan Pérez" required autofocus
                                value="{{ old('name') }}">
                        </div>
                        @error('name')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email">Correo electrónico</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <iconify-icon icon="uil:envelope"></iconify-icon>
                            </span>
                            <input type="email" id="email" name="email" placeholder="tu@example.com" required
                                value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <iconify-icon icon="iconoir:lock"></iconify-icon>
                            </span>
                            <input type="password" id="password" name="password" placeholder="Mínimo 8 caracteres"
                                required>
                            <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                <iconify-icon id="icon-eye-password" icon="iconoir:eye"></iconify-icon>
                            </button>
                        </div>
                        @error('password')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="form-group">
                        <label for="password_confirmation">Confirmar contraseña</label>
                        <div class="input-wrapper">
                            <span class="input-icon">
                                <iconify-icon icon="iconoir:lock"></iconify-icon>
                            </span>
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                placeholder="Repite tu contraseña" required>
                            <button type="button" class="toggle-password"
                                onclick="togglePassword('password_confirmation')">
                                <iconify-icon id="icon-eye-password-confirm" icon="iconoir:eye"></iconify-icon>
                            </button>
                        </div>
                    </div>

                    <!-- Terms and Conditions -->
                    <div class="form-group checkbox-group">
                        <label class="checkbox-container">
                            <input type="checkbox" name="terms" required>
                            <span>Acepto los <a href="{{ asset('assets/pdf/TermsAndCoinditions.pdf') }}" class="link-inline" target="_blank">términos y condiciones</a></span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn-primary">Crear cuenta</button>

                    <!-- Divider -->
                    <div class="divider">
                        <span>O</span>
                    </div>

                    <!-- Google Sign Up Button -->
                    <a href="{{ route('google.redirect') }}" class="btn-google">
                        <iconify-icon icon="material-icon-theme:google"></iconify-icon>
                        Registrarse con Google
                    </a>

                    <!-- Sign In Link -->
                    <div class="auth-footer">
                        ¿Ya tienes cuenta? <a href="{{ route('login') }}" class="link-primary">Inicia sesión</a>
                    </div>
                </form>
            </div>
        </section>

        <!-- Right Column - Branding Section -->
        <section class="auth-branding-section">
            <div class="branding-content">
                <!-- Logo for branding section -->
                <div class="branding-logo">
                    <img src="{{ asset('assets/logo/logo-novex-color.png') }}" alt="Novex Logo" class="header-logo">
                </div>
                <!-- Tagline -->
                <p class="branding-tagline">El espacio de trabajo moderno para que equipos de alto rendimiento colaboren y entreguen resultados.</p>

                <!-- Stats -->
                <div class="branding-stats">
                    <div class="stat-item">
                        <span class="stat-number">10k+</span>
                        <span class="stat-label">Equipos</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">99.9%</span>
                        <span class="stat-label">Disponibilidad</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">24/7</span>
                        <span class="stat-label">Soporte</span>
                    </div>
                </div>
            </div>

            <!-- Decorative Elements -->
            <div class="branding-decoration decoration-1"></div>
            <div class="branding-decoration decoration-2"></div>
        </section>
    </div>

    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const iconElement = passwordInput.parentElement.querySelector('button').querySelector('iconify-icon');
            const isPassword = passwordInput.getAttribute('type') === 'password';
            
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            iconElement.setAttribute('icon', isPassword ? 'iconoir:eye-off' : 'iconoir:eye');
        }

        // Form validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('password_confirmation').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Las contraseñas no coinciden');
            }
        });
    </script>
</body>

</html>
