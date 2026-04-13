<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;

class ControlPanelController extends Controller
{
    /**
     * Muestra el panel de control con la lista de usuarios del tenant actual.
     */
    public function index(): View
    {
        $users = [];

        // Cargar usuarios del tenant solo si tenancy está inicializado
        if (function_exists('tenant') && tenant()) {
            $currentTenant = tenant();
            $users = User::whereHas('memberships', function ($query) use ($currentTenant) {
                $query->where('tenant_id', $currentTenant->id);
            })->get();
        }

        return view('dashboard.features.control-panel.controlPanelApp', compact('users'));
    }
}
