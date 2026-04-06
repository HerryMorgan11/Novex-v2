<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    // ───────────────────────────────── Scopes ─────────────────────────────────

    public function scopeForUser(Builder $query, mixed $user): Builder
    {
        $userId = is_object($user) ? $user->id : $user;

        return $query->where('user_id', $userId);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position')->orderBy('name');
    }

    public function scopeDefault(Builder $query): Builder
    {
        return $query->where('is_default', true);
    }

    // ───────────────────────────────── Métodos ────────────────────────────────

    public function pendingRemindersCount(): int
    {
        return $this->reminders()->where('is_completed', false)->whereNull('deleted_at')->count();
    }

    public function completedRemindersCount(): int
    {
        return $this->reminders()->where('is_completed', true)->count();
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
