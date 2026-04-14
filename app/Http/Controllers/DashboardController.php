<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard principal.
     * Determina si mostrar el modal de creación de empresa en base al estado del tenant del usuario.
     */
    public function index(): View
    {
        $user = auth()->user()->load('memberships:id,user_id,tenant_id,status');

        // Mostrar modal si el usuario no tiene tenant ni membership activo
        $showModal = ! $user->current_tenant_id
            && ! $user->memberships->where('status', 'active')->count();

        return view('dashboard.app.dashboard', [
            'currentConnection' => DB::connection()->getName(),
            'currentDatabase' => DB::connection()->getDatabaseName(),
            'showModal' => $showModal,
        ]);
    }
}
