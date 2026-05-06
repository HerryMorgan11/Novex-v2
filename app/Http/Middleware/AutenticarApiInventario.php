<?php

namespace App\Http\Middleware;

use App\Models\Inventario\ApiTokenInventario;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Autentica endpoints del módulo de inventario mediante Bearer token.
 *
 * Acepta token crudo o ya hasheado (compatibilidad hacia atrás). El token válido
 * se inyecta en el request como `_api_token` para consultas aguas abajo.
 */
class AutenticarApiInventario
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (empty($token)) {
            return response()->json(['error' => 'Token de autenticación requerido.'], 401);
        }

        $apiToken = ApiTokenInventario::query()
            ->where('token', hash('sha256', $token))
            ->orWhere('token', $token)
            ->first();

        if (! $apiToken || ! $apiToken->esValido()) {
            return response()->json(['error' => 'Token inválido o expirado.'], 401);
        }

        $apiToken->update(['ultimo_uso' => now()]);
        $request->merge(['_api_token' => $apiToken]);

        return $next($request);
    }
}
