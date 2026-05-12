<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Controlador base de la aplicación.
 */
abstract class Controller
{
    use AuthorizesRequests;
}
