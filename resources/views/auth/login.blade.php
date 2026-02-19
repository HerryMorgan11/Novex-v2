<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Novex</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/auth/auth.css')
</head>

<body>
    <div class="login-wrapper">
        <!-- Abstract Background Elements -->
        <div class="bg-shape shape-1"></div>
        <div class="bg-shape shape-2"></div>

        <div class="login-container">
            <div class="login-header">
                <h1>Sign In</h1>
                <p>Enter your credentials to continue</p>
            </div>

            <form action="{{ route('login') }}" method="POST" class="login-form">
                @csrf

                <div class="form-group">
                    <label for="email">EMAIL</label>
                    <div class="input-wrapper">
                        <span class="input-icon">
                            <!-- Envelope Icon -->
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="4" width="20" height="16" rx="2"></rect>
                                <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path>
                            </svg>
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
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </span>
                        <input type="password" id="password" name="password" placeholder="Enter your password"
                            required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <!-- Eye Icon -->
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <path
                                    d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0">
                                </path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
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
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M23.766 12.2764C23.766 11.4607 23.6999 10.6406 23.5588 9.83807H12.24V14.4591H18.7217C18.4528 15.9494 17.5885 17.2678 16.323 18.1056V21.1039H20.19C22.4608 19.0139 23.766 15.9274 23.766 12.2764Z"
                            fill="#4285F4" />
                        <path
                            d="M12.2399 24.0008C15.4765 24.0008 18.2058 22.9382 20.1944 21.1039L16.3274 18.1055C15.2516 18.8375 13.8626 19.252 12.2444 19.252C9.11379 19.252 6.45937 17.1399 5.50696 14.3003H1.51651V17.3912C3.55362 21.4434 7.70281 24.0008 12.2399 24.0008Z"
                            fill="#34A853" />
                        <path
                            d="M5.50253 14.3003C5.00236 12.8099 5.00236 11.1961 5.50253 9.70575V6.61481H1.51649C-0.18551 10.0056 -0.18551 14.0004 1.51649 17.3912L5.50253 14.3003Z"
                            fill="#FBBC05" />
                        <path
                            d="M12.2399 4.74966C13.9508 4.7232 15.6043 5.36697 16.8433 6.54867L20.2694 3.12262C18.0999 1.0855 15.2207 -0.0344664 12.2399 0.000808666C7.70281 0.000808666 3.55362 2.55822 1.51651 6.61481L5.50255 9.70575C6.45055 6.86173 9.10935 4.74966 12.2399 4.74966Z"
                            fill="#EA4335" />
                    </svg>
                    Sign in with Google
                </a>

                <div style="margin-top: 24px;" class="alt-action">

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
        }
    </script>
</body>

</html>
