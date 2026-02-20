<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Provisioning</title>
    <link rel="stylesheet" href="/css/app.css">
    <style>
        .center { display:flex; align-items:center; justify-content:center; height:100vh; flex-direction:column; }
    </style>
</head>
<body>
<div class="center">
    <h1>Preparando tu espacio...</h1>
    <div id="spinner" style="margin:20px">
        <svg width="64" height="64" viewBox="0 0 50 50">
            <path fill="#4F46E5" d="M43.935,25.145c0-10.318-8.364-18.682-18.682-18.682c-10.318,0-18.682,8.364-18.682,18.682h4.068
                c0-8.071,6.543-14.614,14.614-14.614c8.071,0,14.614,6.543,14.614,14.614H43.935z">
                <animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 25 25" to="360 25 25" dur="0.9s" repeatCount="indefinite"/>
            </path>
        </svg>
    </div>
    <p id="status">Cargando...</p>
</div>
<script>
    async function checkStatus() {
        try {
            const res = await fetch("{{ route('provisioning.status') }}", { credentials: 'same-origin' });
            if (res.status === 401) {
                document.getElementById('status').textContent = 'No autenticado';
                return;
            }
            const data = await res.json();
            document.getElementById('status').textContent = 'Estado: ' + data.status;

            if (data.status === 'active') {
                // Small delay to show success
                setTimeout(() => { window.location = '{{ route('dashboard') }}'; }, 800);
                return;
            }

            // Poll again
            setTimeout(checkStatus, 3000);
        } catch (err) {
            document.getElementById('status').textContent = 'Error de conexión';
            setTimeout(checkStatus, 5000);
        }
    }

    // Start polling
    checkStatus();
</script>
</body>
</html>
