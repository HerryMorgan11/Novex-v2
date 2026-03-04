<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Novex</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/auth/auth.css', 'resources/js/app.js'])
</head>

<body>
    <div class="login-wrapper" style="--bg-image: url('{{ asset('assets/background/fondo-forms.jpg') }}')">
        <div class="bg-shape shape-1"></div>
        <div class="bg-shape shape-2"></div>

        <div class="login-container">
            <div class="login-header">
                <h1>Verify Email</h1>
                <p style="line-height: 1.5; margin-top: 8px;">Thanks for signing up! Before getting started, could you
                    verify your email address by clicking on the link we just emailed to you?</p>
            </div>

            @if (session('status') == 'verification-link-sent')
                <div style="margin-bottom: 24px; color: #10b981; font-size: 14px; font-weight: 500;">
                    A new verification link has been sent to the email address you provided during registration.
                </div>
            @endif

            <form action="{{ route('verification.send') }}" method="POST" class="login-form">
                @csrf
                <button type="submit" class="btn-primary">Resend Verification Email</button>
            </form>

            <form action="{{ route('logout') }}" method="POST" style="margin-top: 16px; text-align: center;">
                @csrf
                <button type="submit" class="forgot-password"
                    style="background: none; border: none; cursor: pointer; font-size: 14px;">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</body>

</html>
