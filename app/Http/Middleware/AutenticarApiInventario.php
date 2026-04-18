<?php

namespace App\Http\Middleware;

use App\Models\Inventario\ApiTokenInventario;
use Closure;
use Illuminate\Http\Request;

/**
 * Middleware para autenticar los endpoints de API del módulo de inventario.
 * Usa Bearer token almacenado en la tabla api_tokens_inventario del tenant.
 */
class AutenticarApiInventario
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->bearerToken();

        if (empty($token)) {
            return response()->json(['error' => 'Token de autenticación requerido.'], 401);
        }

        $apiToken = ApiTokenInventario::where('token', hash('sha256', $token))
            ->orWhere('token', $token) // compatibilidad sin hash si es token crudo
            ->first();

        if (! $apiToken || ! $apiToken->esValido()) {
            return response()->json(['error' => 'Token inválido o expirado.'], 401);
        }

        // Registrar último uso
        $apiToken->update(['ultimo_uso' => now()]);

        // Inyectar info del token en el request
        $request->merge(['_api_token' => $apiToken]);

        return $next($request);
    }
}
