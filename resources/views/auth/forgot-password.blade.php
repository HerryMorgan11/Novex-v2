<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Novex</title>
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
                <h1>Forgot Password</h1>
                <p>No problem. Just let us know your email address and we will email you a password reset link.</p>
            </div>

            <form action="{{ route('password.email') }}" method="POST" class="login-form">
                @csrf

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
                        <input type="email" id="email" name="email" placeholder="you@example.com"
                            value="{{ old('email') }}" required autofocus>
                    </div>
                </div>

                <div class="form-options">
                    <a href="{{ route('login') }}" class="forgot-password">&lt; Back to Sign In</a>
                </div>

                <button type="submit" class="btn-primary">Email Password Reset Link</button>
            </form>
        </div>
    </div>
</body>

</html>
