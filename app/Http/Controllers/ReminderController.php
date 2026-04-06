<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReminderRequest;
use App\Http\Requests\UpdateReminderRequest;
use App\Models\Reminder;
use App\Models\ReminderList;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReminderController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $query = Reminder::forUser($user)
            ->active()
            ->with(['list', 'tags', 'subtasks'])
            ->ordered();

        // Filtros
        if ($request->filled('list')) {
            $query->byList((int) $request->list);
        }

        if ($request->filled('priority')) {
            $query->byPriority((int) $request->priority);
        }

        if ($request->filled('tag')) {
            $query->whereHas('tags', fn ($q) => $q->where('tags.id', $request->tag));
        }

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
        $tags = Tag::forUser($user)->ordered()->withCount('reminders')->get();

        // Contadores para sidebar
        $todayCount = Reminder::forUser($user)->active()->dueToday()->count();
        $pendingCount = Reminder::forUser($user)->active()->pending()->count();
        $allCount = Reminder::forUser($user)->active()->count();
        $completedCount = Reminder::forUser($user)->completed()->count();
        $overdueCount = Reminder::forUser($user)->active()->overdue()->count();

        return view('dashboard.features.reminders.reminders.index', compact('reminders', 'lists', 'tags', 'filter', 'todayCount', 'pendingCount', 'allCount', 'completedCount', 'overdueCount'));
    }

    public function create(Request $request): View
    {
        $lists = ReminderList::forUser(auth()->user())->ordered()->get();
        $tags = Tag::forUser(auth()->user())->ordered()->get();

        $selectedList = $request->filled('list')
            ? ReminderList::forUser(auth()->user())->find($request->list)
            : null;

        return view('dashboard.features.reminders.reminders.create', compact('lists', 'tags', 'selectedList'));
    }

    public function store(StoreReminderRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['position'] = Reminder::nextPositionForUser(
            auth()->id(),
            $data['reminder_list_id'] ?? null
        );

        $tagIds = $data['tag_ids'] ?? [];
        unset($data['tag_ids']);

        $reminder = Reminder::create($data);
        $reminder->tags()->sync($tagIds);

        return redirect()->route('reminders.show', $reminder)
            ->with('success', 'Recordatorio creado correctamente.');
    }

    public function show(Reminder $reminder): View
    {
        $this->authorize('view', $reminder);

        $reminder->load(['list', 'tags', 'subtasks' => fn ($q) => $q->ordered()]);

        $lists = ReminderList::forUser(auth()->user())->ordered()->get();
        $tags = Tag::forUser(auth()->user())->ordered()->get();

        return view('dashboard.features.reminders.reminders.show', compact('reminder', 'lists', 'tags'));
    }

    public function edit(Reminder $reminder): View
    {
        $this->authorize('update', $reminder);

        $reminder->load(['list', 'tags']);

        $lists = ReminderList::forUser(auth()->user())->ordered()->get();
        $tags = Tag::forUser(auth()->user())->ordered()->get();

        return view('dashboard.features.reminders.reminders.edit', compact('reminder', 'lists', 'tags'));
    }

    public function update(UpdateReminderRequest $request, Reminder $reminder): RedirectResponse
    {
        $this->authorize('update', $reminder);

        $data = $request->validated();
        $tagIds = $data['tag_ids'] ?? [];
        unset($data['tag_ids']);

        $reminder->update($data);
        $reminder->tags()->sync($tagIds);

        return redirect()->route('reminders.show', $reminder)
            ->with('success', 'Recordatorio actualizado correctamente.');
    }

    public function destroy(Reminder $reminder): RedirectResponse
    {
        $this->authorize('delete', $reminder);

        $reminder->delete(); // soft delete

        return redirect()->route('reminders.index')
            ->with('success', 'Recordatorio eliminado.');
    }

    public function complete(Reminder $reminder): RedirectResponse
    {
        $this->authorize('update', $reminder);

        $reminder->complete();

        return back()->with('success', 'Recordatorio marcado como completado.');
    }

    public function uncomplete(Reminder $reminder): RedirectResponse
    {
        $this->authorize('update', $reminder);

        $reminder->uncomplete();

        return back()->with('success', 'Recordatorio desmarcado.');
    }

    public function archive(Reminder $reminder): RedirectResponse
    {
        $this->authorize('update', $reminder);

        $reminder->archive();

        return back()->with('success', 'Recordatorio archivado.');
    }

    public function unarchive(Reminder $reminder): RedirectResponse
    {
        $this->authorize('update', $reminder);

        $reminder->unarchive();

        return back()->with('success', 'Recordatorio restaurado al activo.');
    }

    public function moveToList(Request $request, Reminder $reminder): RedirectResponse
    {
        $this->authorize('update', $reminder);

        $request->validate([
            'reminder_list_id' => [
                'nullable',
                'integer',
                \Illuminate\Validation\Rule::exists('reminder_lists', 'id')
                    ->where('user_id', auth()->id()),
            ],
        ]);

        $reminder->moveToList($request->reminder_list_id);

        return back()->with('success', 'Recordatorio movido a la lista seleccionada.');
    }

    public function restore(int $id): RedirectResponse
    {
        $reminder = Reminder::withTrashed()
            ->forUser(auth()->user())
            ->findOrFail($id);

        $this->authorize('restore', $reminder);

        $reminder->restore();

        return back()->with('success', 'Recordatorio restaurado.');
    }

    public function reorder(Request $request): \Illuminate\Http\JsonResponse
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
