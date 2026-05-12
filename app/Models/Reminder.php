<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Modelo que representa un recordatorio del usuario.
 *
 * Almacenado en la base de datos del tenant. Soporta prioridades,
 * fechas de vencimiento, archivado y subtareas.
 *
 * @property int $id
 * @property string $user_id
 * @property int|null $reminder_list_id
 * @property string $title
 * @property string|null $notes
 * @property bool $is_completed
 * @property Carbon|null $completed_at
 * @property int $priority
 * @property Carbon|null $starts_at
 * @property Carbon|null $due_at
 * @property Carbon|null $remind_at
 * @property bool $all_day
 * @property string $status
 * @property int $position
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read string $priority_label
 * @property-read bool $is_overdue
 * @property-read int $subtasks_completion_percentage
 * @property-read User $user
 * @property-read ReminderList|null $list
 * @property-read Collection<int, Subtask> $subtasks
 */
class Reminder extends Model
{
    use SoftDeletes;

    protected $connection = 'tenant';

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

    /**
     * Obtiene el usuario propietario del recordatorio.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene la lista a la que pertenece el recordatorio.
     */
    public function list(): BelongsTo
    {
        return $this->belongsTo(ReminderList::class, 'reminder_list_id');
    }

    /**
     * Obtiene las subtareas del recordatorio, ordenadas por posición.
     */
    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class)->orderBy('position');
    }

    // ───────────────────────────────── Scopes ─────────────────────────────────

    /**
     * Filtra recordatorios por usuario.
     */
    public function scopeForUser(Builder $query, mixed $user): Builder
    {
        $userId = is_object($user) ? $user->id : $user;

        return $query->where('user_id', $userId);
    }

    /**
     * Filtra solo recordatorios activos.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * Filtra solo recordatorios archivados.
     */
    public function scopeArchived(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ARCHIVED);
    }

    /**
     * Filtra solo recordatorios completados.
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('is_completed', true);
    }

    /**
     * Filtra solo recordatorios pendientes.
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('is_completed', false);
    }

    /**
     * Filtra por lista de recordatorios.
     */
    public function scopeByList(Builder $query, int $listId): Builder
    {
        return $query->where('reminder_list_id', $listId);
    }

    /**
     * Filtra por nivel de prioridad.
     */
    public function scopeByPriority(Builder $query, int $priority): Builder
    {
        return $query->where('priority', $priority);
    }

    /**
     * Filtra recordatorios vencidos y no completados.
     */
    public function scopeOverdue(Builder $query): Builder
    {
        return $query->whereNotNull('due_at')
            ->where('due_at', '<', now())
            ->where('is_completed', false);
    }

    /**
     * Filtra recordatorios con vencimiento hoy.
     */
    public function scopeDueToday(Builder $query): Builder
    {
        return $query->whereDate('due_at', today())
            ->where('is_completed', false);
    }

    /**
     * Filtra recordatorios próximos a vencer en los días indicados.
     */
    public function scopeUpcoming(Builder $query, int $days = 7): Builder
    {
        return $query->whereNotNull('due_at')
            ->whereBetween('due_at', [now(), now()->addDays($days)])
            ->where('is_completed', false);
    }

    /**
     * Ordena por posición, prioridad descendente y fecha de vencimiento.
     */
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

    /**
     * Marca el recordatorio como completado.
     */
    public function complete(): void
    {
        $this->update([
            'is_completed' => true,
            'completed_at' => now(),
        ]);
    }

    /**
     * Desmarca el recordatorio como completado.
     */
    public function uncomplete(): void
    {
        $this->update([
            'is_completed' => false,
            'completed_at' => null,
        ]);
    }

    /**
     * Archiva el recordatorio.
     */
    public function archive(): void
    {
        $this->update(['status' => self::STATUS_ARCHIVED]);
    }

    /**
     * Restaura el recordatorio desde el estado archivado.
     */
    public function unarchive(): void
    {
        $this->update(['status' => self::STATUS_ACTIVE]);
    }

    /**
     * Mueve el recordatorio a otra lista.
     */
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
