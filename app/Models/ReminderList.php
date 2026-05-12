<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa una lista de recordatorios del usuario.
 *
 * Permite agrupar recordatorios por categoría o contexto.
 *
 * @property int $id
 * @property string $user_id
 * @property string $name
 * @property string|null $color
 * @property string|null $icon
 * @property bool $is_default
 * @property int $position
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read User $user
 * @property-read Collection<int, Reminder> $reminders
 */
class ReminderList extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'color',
        'icon',
        'is_default',
        'position',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'position' => 'integer',
    ];

    // ───────────────────────────────── Relaciones ─────────────────────────────

    /**
     * Obtiene el usuario propietario de la lista.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene los recordatorios asociados a esta lista.
     */
    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    // ───────────────────────────────── Scopes ─────────────────────────────────

    /**
     * Filtra listas por usuario.
     */
    public function scopeForUser(Builder $query, mixed $user): Builder
    {
        $userId = is_object($user) ? $user->id : $user;

        return $query->where('user_id', $userId);
    }

    /**
     * Ordena por posición y nombre.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position')->orderBy('name');
    }

    /**
     * Filtra solo listas marcadas como predeterminadas.
     */
    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    // ───────────────────────────────── Métodos ────────────────────────────────

    /**
     * Obtiene el número de recordatorios pendientes en la lista.
     */
    public function pendingRemindersCount(): int
    {
        return $this->reminders()->where('is_completed', false)->whereNull('deleted_at')->count();
    }

    /**
     * Obtiene el número de recordatorios completados en la lista.
     */
    public function completedRemindersCount(): int
    {
        return $this->reminders()->where('is_completed', true)->count();
    }

    /**
     * Obtiene el número de recordatorios vencidos en la lista.
     */
    public function overdueRemindersCount(): int
    {
        return $this->reminders()
            ->whereNotNull('due_at')
            ->where('due_at', '<', now())
            ->where('is_completed', false)
            ->whereNull('deleted_at')
            ->count();
    }

    /**
     * Obtiene el número total de recordatorios no eliminados en la lista.
     */
    public function totalRemindersCount(): int
    {
        return $this->reminders()->whereNull('deleted_at')->count();
    }

    /**
     * Cuando se establece esta lista como por defecto, se quita el flag
     * de cualquier otra lista del mismo usuario.
     */
    public function makeDefault(): void
    {
        static::where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);

        $this->update(['is_default' => true]);
    }

    /**
     * Devuelve la siguiente posición disponible para el usuario.
     */
    public static function nextPositionForUser(string $userId): int
    {
        return (static::where('user_id', $userId)->max('position') ?? -1) + 1;
    }
}
