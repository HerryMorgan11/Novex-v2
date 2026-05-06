<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @vite(['resources/css/dashboard/shared/sidebar.css'])
    @vite(['resources/css/dashboard/features/dashboard-main/general-dashboard.css'])
</head>
<body>
    @include('dashboard.shared.sidebar')

    <main class="main-layout">
        <div class="main-panel">
            <h1>
                Bienvenido a Novex
                <small class="dash-debug-small">
                    | Conexión: {{ $currentConnection ?? 'N/A' }} | DB activa: {{ $currentDatabase ?? 'N/A' }}
                </small>
            </h1>

            <h2>Tenancy debug</h2>
            <pre class="dash-debug-pre">
{{ json_encode([
    'initialized' => $tenancyInitialized,
    'tenant' => $tenancyTenant ? [
        'id' => $tenancyTenant->id ?? null,
        'name' => $tenancyTenant->name ?? null,
        'slug' => $tenancyTenant->slug ?? null,
        'db_name' => $tenancyTenant->db_name ?? null,
        'status' => $tenancyTenant->status ?? null,
    ] : null,
    'error' => $tenancyError,
], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}
            </pre>

            <form method="POST" action="{{ url('/logout', [], request()->isSecure()) }}" class="dash-debug-logout-form">
                @csrf
                <button type="submit" class="dash-debug-logout-btn">Logout</button>
            </form>
        </div>
    </main>
</body>
</html>
