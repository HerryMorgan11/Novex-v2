<?php

namespace App\Http\Controllers;

use App\Http\Requests\Reminders\StoreReminderRequest;
use App\Http\Requests\Reminders\UpdateReminderRequest;
use App\Models\Reminder;
use App\Models\ReminderList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ReminderController extends Controller
{
    /**
     * Lista de recordatorios con filtros opcionales (lista, prioridad, estado).
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        $query = Reminder::forUser($user)
            ->active()
            ->with(['list', 'subtasks'])
            ->ordered();

        // Filtro por lista
        if ($request->filled('list')) {
            $query->byList((int) $request->list);
        }

        // Filtro por prioridad
        if ($request->filled('priority')) {
            $query->byPriority((int) $request->priority);
        }

        // Filtro de estado: pending / completed / overdue / today / all
        $filter = $request->get('filter', 'pending');
        match ($filter) {
            'completed' => $query->completed(),
            'overdue' => $query->overdue(),
            'today' => $query->dueToday(),
            'all' => null,
            default => $query->pending(),
        };

        $reminders = $query->paginate(20)->withQueryString();

        $lists = ReminderList::forUser($user)->ordered()->withCount('reminders')->get();

        $currentList = $request->filled('list')
            ? ReminderList::forUser($user)->find((int) $request->list)
            : null;

        // Contadores para el sidebar de filtros rápidos - optimizados con una sola query
        $counters = Reminder::forUser($user)
            ->selectRaw('
                COUNT(CASE WHEN status = ? THEN 1 END) as total_active,
                COUNT(CASE WHEN DATE(due_at) = CURDATE() AND status = ? THEN 1 END) as today_count,
                COUNT(CASE WHEN is_completed = 0 AND status = ? THEN 1 END) as pending_count,
                COUNT(CASE WHEN is_completed = 1 THEN 1 END) as completed_count,
                COUNT(CASE WHEN DATE(due_at) < CURDATE() AND is_completed = 0 AND status = ? THEN 1 END) as overdue_count
            ', [
                Reminder::STATUS_ACTIVE,
                Reminder::STATUS_ACTIVE,
                Reminder::STATUS_ACTIVE,
                Reminder::STATUS_ACTIVE,
            ])
            ->first();

        $todayCount = $counters->today_count ?? 0;
        $pendingCount = $counters->pending_count ?? 0;
        $allCount = $counters->total_active ?? 0;
        $completedCount = $counters->completed_count ?? 0;
        $overdueCount = $counters->overdue_count ?? 0;

        return view('dashboard.features.reminders.reminders.index', compact(
            'reminders', 'lists', 'filter',
            'todayCount', 'pendingCount', 'allCount',
            'completedCount', 'overdueCount', 'currentList'
        ));
    }

    /**
     * Formulario de creación de recordatorio.
     */
    public function create(Request $request): View
    {
        $lists = ReminderList::forUser(auth()->user())->ordered()->get();

        $selectedList = $request->filled('list')
            ? ReminderList::forUser(auth()->user())->find($request->list)
            : null;

        return view('dashboard.features.reminders.reminders.create', compact('lists', 'selectedList'));
    }

    /**
     * Guarda un nuevo recordatorio en la base de datos.
     */
    public function store(StoreReminderRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['position'] = Reminder::nextPositionForUser(
            auth()->id(),
            $data['reminder_list_id'] ?? null
        );

        $reminder = Reminder::create($data);

        return redirect()->route('reminders.show', $reminder)
            ->with('success', 'Recordatorio creado correctamente.');
    }

    /**
     * Muestra el detalle de un recordatorio con sus subtareas.
     */
    public function show(Reminder $reminder): View
    {
        $this->authorize('view', $reminder);

        $reminder->load(['list', 'subtasks' => fn ($q) => $q->ordered()]);

        $lists = ReminderList::forUser(auth()->user())->ordered()->get();

        return view('dashboard.features.reminders.reminders.show', compact('reminder', 'lists'));
    }

    /**
     * Formulario de edición de recordatorio.
     */
    public function edit(Reminder $reminder): View
    {
        $this->authorize('update', $reminder);

        $reminder->load('list');

        $lists = ReminderList::forUser(auth()->user())->ordered()->get();

        return view('dashboard.features.reminders.reminders.edit', compact('reminder', 'lists'));
    }

    /**
     * Actualiza un recordatorio existente.
     */
    public function update(UpdateReminderRequest $request, Reminder $reminder): RedirectResponse
    {
        $this->authorize('update', $reminder);

        $reminder->update($request->validated());

        return redirect()->route('reminders.show', $reminder)
            ->with('success', 'Recordatorio actualizado correctamente.');
    }

    /**
     * Elimina un recordatorio (soft delete para conservar historial).
     */
    public function destroy(Reminder $reminder): RedirectResponse
    {
        $this->authorize('delete', $reminder);

        $reminder->delete();

        return redirect()->route('reminders.index')
            ->with('success', 'Recordatorio eliminado.');
    }

    /**
     * Marca un recordatorio como completado.
     */
    public function complete(Reminder $reminder): RedirectResponse
    {
        $this->authorize('update', $reminder);
        $reminder->complete();

        return back()->with('success', 'Recordatorio marcado como completado.');
    }

    /**
     * Desmarca un recordatorio completado.
     */
    public function uncomplete(Reminder $reminder): RedirectResponse
    {
        $this->authorize('update', $reminder);
        $reminder->uncomplete();

        return back()->with('success', 'Recordatorio desmarcado.');
    }

    /**
     * Archiva un recordatorio (oculto de la vista principal).
     */
    public function archive(Reminder $reminder): RedirectResponse
    {
        $this->authorize('update', $reminder);
        $reminder->archive();

        return back()->with('success', 'Recordatorio archivado.');
    }

    /**
     * Restaura un recordatorio archivado al estado activo.
     */
    public function unarchive(Reminder $reminder): RedirectResponse
    {
        $this->authorize('update', $reminder);
        $reminder->unarchive();

        return back()->with('success', 'Recordatorio restaurado al activo.');
    }

    /**
     * Mueve un recordatorio a otra lista (o sin lista).
     */
    public function moveToList(Request $request, Reminder $reminder): RedirectResponse
    {
        $this->authorize('update', $reminder);

        $request->validate([
            'reminder_list_id' => [
                'nullable',
                'integer',
                Rule::exists('reminder_lists', 'id')->where('user_id', auth()->id()),
            ],
        ]);

        $reminder->moveToList($request->reminder_list_id);

        return back()->with('success', 'Recordatorio movido a la lista seleccionada.');
    }

    /**
     * Restaura un recordatorio que había sido eliminado (soft deleted).
     */
    public function restore(int $id): RedirectResponse
    {
        $reminder = Reminder::withTrashed()
            ->forUser(auth()->user())
            ->findOrFail($id);

        $this->authorize('restore', $reminder);

        $reminder->restore();

        return back()->with('success', 'Recordatorio restaurado.');
    }

    /**
     * Actualiza el orden de los recordatorios según un array de IDs.
     */
    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        foreach ($request->ids as $position => $id) {
            Reminder::forUser(auth()->user())
                ->where('id', $id)
                ->update(['position' => $position]);
        }

        return response()->json(['success' => true]);
    }
}
