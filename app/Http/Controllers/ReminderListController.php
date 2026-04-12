<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReminderListRequest;
use App\Http\Requests\UpdateReminderListRequest;
use App\Models\ReminderList;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReminderListController extends Controller
{
    public function index(): View
    {
        $lists = ReminderList::forUser(auth()->user())
            ->ordered()
            ->get()
            ->each(fn ($list) => $list->append([]));

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

        if (! empty($data['is_default'])) {
            $list->makeDefault();
        }

        return redirect()->route('reminders.lists.index')
            ->with('success', 'Lista creada correctamente.');
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

        return redirect()->route('reminders.lists.index')
            ->with('success', 'Lista actualizada correctamente.');
    }

    public function destroy(ReminderList $reminderList): RedirectResponse
    {
        $this->authorize('delete', $reminderList);

        // Los recordatorios quedan con reminder_list_id = null (nullOnDelete)
        $reminderList->delete();

        return redirect()->route('reminders.lists.index')
            ->with('success', 'Lista eliminada. Sus recordatorios fueron conservados sin lista.');
    }

    public function reorder(Request $request): \Illuminate\Http\JsonResponse
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
}
