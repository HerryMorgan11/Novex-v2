<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    public function reminder(): BelongsTo
    {
        return $this->belongsTo(Reminder::class);
    }

    // ───────────────────────────────── Scopes ─────────────────────────────────

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('is_completed', true);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('is_completed', false);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position');
    }

    // ───────────────────────────────── Métodos ────────────────────────────────

    public function complete(): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);
    }

    public function uncomplete(): void
    {
        $this->update([
            'is_completed' => false,
            'completed_at' => null,
        ]);
    }

    public static function nextPositionForReminder(int $reminderId): int
    {
        return (static::where('reminder_id', $reminderId)->max('position') ?? -1) + 1;
    }
}
