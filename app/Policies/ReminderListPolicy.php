<?php

namespace App\Policies;

use App\Models\ReminderList;
use App\Models\User;

class ReminderListPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ReminderList $list): bool
    {
        return $list->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ReminderList $list): bool
    {
        return $list->user_id === $user->id;
    }

    public function delete(User $user, ReminderList $list): bool
    {
        // No se puede eliminar la lista por defecto
        return $list->user_id === $user->id && ! $list->is_default;
    }
}
