<?php

namespace App\Http\Controllers\controlPanel;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserCompany extends Controller
{
    public function UserControl()
    {
        $users = User::all();

        return view('dashboard.features.control-panel.controlPanelApp', compact('users'));
    }
}
