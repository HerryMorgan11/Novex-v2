<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Provisioning - Novex</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/auth/auth.css', 'resources/js/app.js'])
</head>

<body>
    <div class="login-wrapper" style="--bg-image: url('{{ Vite::asset('resources/images/background/fondo-forms.jpg') }}')">
        <div class="login-container" style="max-width:540px; text-align:center">
            <img src="{{ Vite::asset('resources/images/logo/logo-novex-color.png') }}" alt="Novex Logo" class="logo" style="height:64px; margin-bottom:18px">

            <h1>Provisioning your workspace</h1>
            <p id="message">We're preparing your tenant. This may take a minute.</p>

            <div id="status" style="margin-top:16px; font-weight:600">Status: provisioning</div>
            <div id="db" style="margin-top:8px; color: #6b7280"></div>

            <div style="margin-top:20px">
                <a href="/" class="btn-secondary">&lt; Back to Home</a>
            </div>
        </div>
    </div>

    <script>
        const statusEl = document.getElementById('status');
        const dbEl = document.getElementById('db');
        const messageEl = document.getElementById('message');

        async function checkStatus() {
            try {
                const res = await fetch("{{ route('provisioning.status') }}", { credentials: 'same-origin' });
                if (!res.ok) throw new Error('not-ok');
                const data = await res.json();
                statusEl.textContent = 'Status: ' + data.status;
                dbEl.textContent = data.db_name ? ('Database: ' + data.db_name) : '';

                if (data.status !== 'provisioning') {
                    // Provisioning finished — redirect to app
                    window.location.href = '/app';
                }
            } catch (e) {
                // ignore transient errors
                console.warn('Provisioning status check failed', e);
            }
        }

        // Poll every 2 seconds
        checkStatus();
        const interval = setInterval(checkStatus, 2000);
    </script>

</body>
</html>
