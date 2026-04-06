<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reminder extends Model
{
    use SoftDeletes;

    // Prioridades
    const PRIORITY_NONE = 0;

    const PRIORITY_LOW = 1;

    const PRIORITY_MEDIUM = 2;

    const PRIORITY_HIGH = 3;

    // Estados
    const STATUS_ACTIVE = 'active';

    const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'user_id',
        'reminder_list_id',
        'title',
        'notes',
        'is_completed',
        'completed_at',
        'priority',
        'starts_at',
        'due_at',
        'remind_at',
        'all_day',
        'status',
        'position',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'all_day' => 'boolean',
        'completed_at' => 'datetime',
        'starts_at' => 'datetime',
        'due_at' => 'datetime',
        'remind_at' => 'datetime',
        'priority' => 'integer',
        'position' => 'integer',
    ];

    // ───────────────────────────────── Relaciones ─────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function list(): BelongsTo
    {
        return $this->belongsTo(ReminderList::class, 'reminder_list_id');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class)->orderBy('position');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'reminder_tag');
    }

    // ───────────────────────────────── Scopes ─────────────────────────────────

    public function scopeForUser(Builder $query, mixed $user): Builder
    {
        $userId = is_object($user) ? $user->id : $user;

        return $query->where('user_id', $userId);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ARCHIVED);
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('is_completed', true);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('is_completed', false);
    }

    public function scopeByList(Builder $query, int $listId): Builder
    {
        return $query->where('reminder_list_id', $listId);
    }

    public function scopeByPriority(Builder $query, int $priority): Builder
    {
        return $query->where('priority', $priority);
    }

    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereNotNull('due_at')
            ->where('due_at', '<', now())
            ->where('is_completed', false);
    }

    public function scopeDueToday(Builder $query): Builder
    {
        return $query->whereDate('due_at', today())
            ->where('is_completed', false);
    }

    public function scopeUpcoming(Builder $query, int $days = 7): Builder
    {
        return $query->whereNotNull('due_at')
            ->whereBetween('due_at', [now(), now()->addDays($days)])
            ->where('is_completed', false);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('position')->orderByDesc('priority')->orderBy('due_at');
    }

    // ───────────────────────────────── Accessors ──────────────────────────────

    public function getPriorityLabelAttribute(): string
    {
        return match ($this->priority) {
            self::PRIORITY_HIGH => 'High',
            self::PRIORITY_MEDIUM => 'Medium',
            self::PRIORITY_LOW => 'Low',
            default => 'None',
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_at !== null
            && $this->due_at->isPast()
            && ! $this->is_completed;
    }

    public function getSubtasksCompletionPercentageAttribute(): int
    {
        $total = $this->subtasks->count();
        if ($total === 0) {
            return 0;
        }

        return (int) round(($this->subtasks->where('is_completed', true)->count() / $total) * 100);
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

    public function archive(): void
    {
        $this->update(['status' => self::STATUS_ARCHIVED]);
    }

    public function unarchive(): void
    {
        $this->update(['status' => self::STATUS_ACTIVE]);
    }

    public function moveToList(?int $listId): void
    {
        $this->update(['reminder_list_id' => $listId]);
    }

    /**
     * Devuelve la siguiente posición disponible para el usuario en una lista.
     */
    public static function nextPositionForUser(string $userId, ?int $listId = null): int
    {
        return (static::where('user_id', $userId)
            ->where('reminder_list_id', $listId)
            ->max('position') ?? -1) + 1;
    }
}
