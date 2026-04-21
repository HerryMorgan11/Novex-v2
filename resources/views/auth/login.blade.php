<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Novex</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/auth/auth.css', 'resources/js/app.js'])
</head>

<body>
        <!-- Abstract Background Elements -->
        <div class="bg-shape shape-1"></div>
        <div class="bg-shape shape-2"></div>

        <div class="login-container">
            <img src="{{ asset('assets/logo/logo-novex-color.png') }}" alt="Novex Logo" class="logo">
            <h2 class="title-sing">Sign in to Novex</h2>
            <form action="{{ route('login') }}" method="POST" class="login-form">
                @csrf

                <div class="form-group">
                    <label for="email">EMAIL</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <!-- Envelope Icon -->
                            <iconify-icon icon="uil:envelope"></iconify-icon>
                        </span>
                        </span>
                        <input type="email" id="email" name="email" placeholder="you@example.com" required
                            autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">PASSWORD</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <!-- Lock Icon -->
                            <iconify-icon icon="iconoir:lock"></iconify-icon>
                        </span>
                        <input type="password" id="password" name="password" placeholder="Enter your password"
                            required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <iconify-icon id="icon-eye-password" icon="iconoir:eye"></iconify-icon>
                        </button>
                    </div>
                </div>

                <div class="form-options">
                    <label class="checkbox-container">
                        <input type="checkbox" name="remember">
                        <span class="checkmark"></span>
                        Remember me
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-password">Forgot password?</a>
                </div>

                <button type="submit" class="btn-primary">Sign In</button>

                <div class="divider">
                    <span>OR</span>
                </div>

                <a href="{{ route('google.redirect') }}" class="btn-google">
                    <iconify-icon icon="material-icon-theme:google"></iconify-icon>
                    Sign in with Google
                </a>

                <div class="alt-action">

                    Don't have an account? <a href="{{ route('register') }}">Create one</a>
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
