<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserCompany extends Controller
{
    /**
     * Obtiene los usuarios del tenant actual
     *
     * Filtra usuarios por el tenant_id a través de la relación memberships.
     * Usa el tenant() inicializado por el middleware checkHasTenant.
     */
    public function UserControl()
    {
        $tenantId = tenant()->id;

        $users = User::where('current_tenant_id', $tenantId)->get();

        return view('dashboard.features.control-panel.controlPanelApp', compact('users'));
    }
}
