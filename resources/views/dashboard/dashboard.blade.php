<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
</head>

<body>
    <h1>
        Bienvenido a Novex
        <small style="font-size: 14px; font-weight: normal;">
            | Conexión: {{ $currentConnection ?? 'N/A' }} | DB activa: {{ $currentDatabase ?? 'N/A' }}
        </small>
    </h1>

    <h2>Tenancy debug</h2>
    <pre style="background: #f6f8fa; padding: 12px; border-radius: 8px;">
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

    <form method="POST" action="{{ route('logout') }}" style="display:inline">
        @csrf
        <button type="submit" style="background:none;border:none;padding:0;color:#3490dc;cursor:pointer">Logout</button>
    </form>
</body>

</html>
