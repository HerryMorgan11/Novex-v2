<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Novex</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="preload" as="image" href="{{ Vite::asset('resources/images/background/fondo-forms.jpg') }}">
    @vite(['resources/css/auth/auth.css', 'resources/js/app.js'])
</head>

<body>
    <div class="login-wrapper" style="--bg-image: url('{{ Vite::asset('resources/images/background/fondo-forms.jpg') }}')">
        <div class="bg-shape shape-1"></div>
        <div class="bg-shape shape-2"></div>

        <div class="login-container">
            <div class="login-header">
                <h1>Create Account</h1>
                <p>Join us to get started</p>
            </div>

            <form action="{{ route('register') }}" method="POST" class="login-form">
                @csrf

                <!-- Name -->
                <div class="form-group">
                    <label for="name">FULL NAME</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <iconify-icon icon="lucide:user"></iconify-icon>
                        </span>
                        <input type="text" id="name" name="name" placeholder="John Doe" required autofocus>
                    </div>
                </div>

                <!-- Email -->
                <div class="form-group">
                    <label for="email">EMAIL</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                            </svg>
                        </span>
                        <input type="email" id="email" name="email" placeholder="you@example.com" required>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">PASSWORD</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <iconify-icon icon="iconoir:lock"></iconify-icon>
                        </span>
                        <input type="password" id="password" name="password" placeholder="Create a password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <iconify-icon id="icon-eye-password" icon="iconoir:eye"></iconify-icon>
                        </button>
                    </div>
                </div>

                <!-- Confirm Password -->
                <div class="form-group">
                    <label for="password_confirmation">CONFIRM PASSWORD</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <iconify-icon icon="iconoir:lock"></iconify-icon>
                        </span>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            placeholder="Confirm your password" required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <iconify-icon id="icon-eye-password" icon="iconoir:eye"></iconify-icon>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox" name="terms" required>
                        <span class="checkmark"></span>
                        I agree to the <a href="#" class="forgot-password" style="margin-left: 4px;">Terms &
                            Conditions</a>
                    </label>
                </div>

                <button type="submit" class="btn-primary">Register</button>

                <div class="divider">
                    <span>OR</span>
                </div>

                <div class="alt-action">
                    Already have an account? <a href="{{ route('login') }}">Sign In</a>
                </div>
            </form>

            <div class="back-home">
                <a href="/">
                    &lt; Back to Home
                </a>
            </div>
        </div>
    </div>

      <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            document.getElementById('icon-eye-password').setAttribute('icon', type === 'password' ? 'iconoir:eye' : 'codicon:eye-closed');
        }
    </script>

</body>
</html>
