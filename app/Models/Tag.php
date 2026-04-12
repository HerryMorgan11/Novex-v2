<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'color',
    ];

    // ───────────────────────────────── Relaciones ─────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reminders(): BelongsToMany
    {
        return $this->belongsToMany(Reminder::class, 'reminder_tag');
    }

    // ───────────────────────────────── Scopes ─────────────────────────────────

    public function scopeForUser(Builder $query, mixed $user): Builder
    {
        $userId = is_object($user) ? $user->id : $user;

        return $query->where('user_id', $userId);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('name');
    }

    // ───────────────────────────────── Métodos ────────────────────────────────

    public function usageCount(): int
    {
        return $this->reminders()->count();
    }
}
