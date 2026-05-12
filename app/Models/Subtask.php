<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa una subtarea asociada a un recordatorio.
 *
 * @property int $id
 * @property int $reminder_id
 * @property string $title
 * @property bool $is_completed
 * @property Carbon|null $completed_at
 * @property int $position
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Reminder $reminder
 */
class Subtask extends Model
{
    protected $fillable = [
        'reminder_id',
        'title',
        'is_completed',
        'completed_at',
        'position',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
        'position' => 'integer',
    ];

    // ───────────────────────────────── Relaciones ─────────────────────────────

    /**
     * Obtiene el recordatorio al que pertenece esta subtarea.
     */
    public function reminder(): BelongsTo
    {
        return $this->belongsTo(Reminder::class);
    }

    // ───────────────────────────────── Scopes ─────────────────────────────────

    /**
     * Filtra subtareas completadas.
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('is_completed', true);
    }

    /**
     * Filtra subtareas pendientes.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('is_completed', false);
    }

    /**
     * Ordena por posición.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position');
    }

    // ───────────────────────────────── Métodos ────────────────────────────────

    /**
     * Marca la subtarea como completada.
     */
    public function complete(): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);
    }

    /**
     * Desmarca la subtarea como completada.
     */
    public function uncomplete(): void
    {
        $this->update([
            'is_completed' => false,
            'completed_at' => null,
        ]);
    }

    /**
     * Devuelve la siguiente posición disponible para un recordatorio.
     */
    public static function nextPositionForReminder(int $reminderId): int
    {
        return (static::where('reminder_id', $reminderId)->max('position') ?? -1) + 1;
    }
}
