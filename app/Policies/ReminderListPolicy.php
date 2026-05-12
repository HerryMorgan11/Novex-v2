<?php

namespace App\Policies;

use App\Models\ReminderList;
use App\Models\User;

/**
 * Política de autorización para listas de recordatorios.
 *
 * Solo permite operar sobre listas que pertenecen al usuario autenticado.
 */
class ReminderListPolicy
{
    /**
     * Determina si el usuario puede ver el listado de listas.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determina si el usuario puede ver una lista específica.
     */
    public function view(User $user, ReminderList $list): bool
    {
        return $list->user_id === $user->id;
    }

    /**
     * Determina si el usuario puede crear listas.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determina si el usuario puede actualizar la lista indicada.
     */
    public function update(User $user, ReminderList $list): bool
    {
        return $list->user_id === $user->id;
    }

    /**
     * Determina si el usuario puede eliminar la lista indicada.
     *
     * No permite eliminar la lista predeterminada.
     */
    public function delete(User $user, ReminderList $list): bool
    {
        // No se puede eliminar la lista por defecto
        return $list->user_id === $user->id && ! $list->is_default;
    }
}
