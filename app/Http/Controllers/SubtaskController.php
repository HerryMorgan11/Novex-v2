<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSubtaskRequest;
use App\Http\Requests\UpdateSubtaskRequest;
use App\Models\Reminder;
use App\Models\Subtask;
use Illuminate\Http\RedirectResponse;

class SubtaskController extends Controller
{
    public function store(StoreSubtaskRequest $request, Reminder $reminder): RedirectResponse
    {
        $this->authorize('update', $reminder);

        Subtask::create([
            'reminder_id' => $reminder->id,
            'title' => $request->validated('title'),
            'position' => Subtask::nextPositionForReminder($reminder->id),
            'is_completed' => false,
        ]);

        return back()->with('success', 'Subtarea añadida.');
    }

    public function update(UpdateSubtaskRequest $request, Reminder $reminder, Subtask $subtask): RedirectResponse
    {
        abort_unless($subtask->reminder_id === $reminder->id, 404);
        $this->authorize('update', $subtask);

        $subtask->update($request->validated());

        return back()->with('success', 'Subtarea actualizada.');
    }

    public function destroy(Reminder $reminder, Subtask $subtask): RedirectResponse
    {
        abort_unless($subtask->reminder_id === $reminder->id, 404);
        $this->authorize('delete', $subtask);

        $subtask->delete();

        return back()->with('success', 'Subtarea eliminada.');
    }

    public function complete(Reminder $reminder, Subtask $subtask): RedirectResponse
    {
        abort_unless($subtask->reminder_id === $reminder->id, 404);
        $this->authorize('update', $subtask);

        $subtask->complete();

        return back();
    }

    public function uncomplete(Reminder $reminder, Subtask $subtask): RedirectResponse
    {
        abort_unless($subtask->reminder_id === $reminder->id, 404);
        $this->authorize('update', $subtask);

        $subtask->uncomplete();

        return back();
    }

    public function reorder(Reminder $reminder): \Illuminate\Http\JsonResponse
    {
        $this->authorize('update', $reminder);

        request()->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        foreach (request('ids') as $position => $id) {
            Subtask::where('reminder_id', $reminder->id)
                ->where('id', $id)
                ->update(['position' => $position]);
        }

        return response()->json(['success' => true]);
    }
}
