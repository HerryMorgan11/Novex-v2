<?php

namespace App\Http\Controllers;

use App\Http\Requests\Subtasks\StoreSubtaskRequest;
use App\Http\Requests\Subtasks\UpdateSubtaskRequest;
use App\Models\Reminder;
use App\Models\Subtask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Gestión de subtareas anidadas bajo un recordatorio.
 *
 * Todas las acciones validan que la subtarea pertenece al recordatorio recibido
 * por ruta para evitar acceso cruzado entre recordatorios del mismo usuario.
 */
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
        $this->ensureBelongsTo($subtask, $reminder);
        $this->authorize('update', $subtask);

        $subtask->update($request->validated());

        return back()->with('success', 'Subtarea actualizada.');
    }

    public function destroy(Reminder $reminder, Subtask $subtask): RedirectResponse
    {
        $this->ensureBelongsTo($subtask, $reminder);
        $this->authorize('delete', $subtask);

        $subtask->delete();

        return back()->with('success', 'Subtarea eliminada.');
    }

    public function complete(Reminder $reminder, Subtask $subtask): RedirectResponse
    {
        $this->ensureBelongsTo($subtask, $reminder);
        $this->authorize('update', $subtask);

        $subtask->complete();

        return back();
    }

    public function uncomplete(Reminder $reminder, Subtask $subtask): RedirectResponse
    {
        $this->ensureBelongsTo($subtask, $reminder);
        $this->authorize('update', $subtask);

        $subtask->uncomplete();

        return back();
    }

    public function reorder(Request $request, Reminder $reminder): JsonResponse
    {
        $this->authorize('update', $reminder);

        $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer'],
        ]);

        foreach ($request->ids as $position => $id) {
            Subtask::where('reminder_id', $reminder->id)
                ->where('id', $id)
                ->update(['position' => $position]);
        }

        return response()->json(['success' => true]);
    }

    /**
     * Aborta 404 si la subtarea no pertenece al recordatorio de la URL.
     * Evita que un usuario manipule el ID y edite subtareas ajenas.
     */
    private function ensureBelongsTo(Subtask $subtask, Reminder $reminder): void
    {
        abort_unless($subtask->reminder_id === $reminder->id, 404);
    }
}
