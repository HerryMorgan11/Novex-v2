<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReminderLists\StoreReminderListRequest;
use App\Http\Requests\ReminderLists\UpdateReminderListRequest;
use App\Models\ReminderList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * CRUD de listas de recordatorios del usuario autenticado.
 *
 * Al eliminar una lista los recordatorios se conservan sin lista asignada
 * (la migración usa nullOnDelete sobre reminder_list_id).
 */
class ReminderListController extends Controller
{
    public function index(): View
    {
        $lists = ReminderList::forUser(auth()->user())->ordered()->get();

        return view('dashboard.features.reminders.lists.index', compact('lists'));
    }

    public function create(): View
    {
        return view('dashboard.features.reminders.lists.create');
    }

    public function store(StoreReminderListRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = auth()->id();
        $data['position'] = ReminderList::nextPositionForUser(auth()->id());

        $list = ReminderList::create($data);

        // makeDefault() asegura que solo una lista del usuario tenga is_default = true.
        if (! empty($data['is_default'])) {
            $list->makeDefault();
        }

        return $this->redirectToIndex('Lista creada correctamente.');
    }

    public function edit(ReminderList $reminderList): View
    {
        $this->authorize('update', $reminderList);

        return view('dashboard.features.reminders.lists.edit', ['list' => $reminderList]);
    }

    public function update(UpdateReminderListRequest $request, ReminderList $reminderList): RedirectResponse
    {
        $this->authorize('update', $reminderList);

        $reminderList->update($request->validated());

        if ($request->boolean('is_default')) {
            $reminderList->makeDefault();
        }

        return $this->redirectToIndex('Lista actualizada correctamente.');
    }

    public function destroy(ReminderList $reminderList): RedirectResponse
    {
        $this->authorize('delete', $reminderList);

        $reminderList->delete();

        return $this->redirectToIndex('Lista eliminada. Sus recordatorios se conservaron sin lista.');
    }

    public function reorder(Request $request): JsonResponse
    {
        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        foreach ($request->ids as $position => $id) {
            ReminderList::forUser(auth()->user())
                ->where('id', $id)
                ->update(['position' => $position]);
        }

        return response()->json(['success' => true]);
    }

    private function redirectToIndex(string $message): RedirectResponse
    {
        return redirect()->route('reminders.lists.index')->with('success', $message);
    }
}
