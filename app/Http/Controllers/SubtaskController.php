<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\Subtask;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    /**
     * Añade una subtarea a un recordatorio.
     */
    public function store(Request $request, Reminder $reminder): RedirectResponse
    {
        $this->authorize('update', $reminder);

        $data = $request->validate($this->subtaskRules());

        Subtask::create([
            'reminder_id' => $reminder->id,
            'title' => $data['title'],
            'position' => Subtask::nextPositionForReminder($reminder->id),
            'is_completed' => false,
        ]);

        return back()->with('success', 'Subtarea añadida.');
    }

    /**
     * Actualiza una subtarea existente.
     */
    public function update(Request $request, Reminder $reminder, Subtask $subtask): RedirectResponse
    {
        abort_unless($subtask->reminder_id === $reminder->id, 404);
        $this->authorize('update', $subtask);

        $subtask->update($request->validate($this->subtaskRules()));

        return back()->with('success', 'Subtarea actualizada.');
    }

    /**
     * Elimina una subtarea.
     */
    public function destroy(Reminder $reminder, Subtask $subtask): RedirectResponse
    {
        abort_unless($subtask->reminder_id === $reminder->id, 404);
        $this->authorize('delete', $subtask);

        $subtask->delete();

        return back()->with('success', 'Subtarea eliminada.');
    }

    /**
     * Marca una subtarea como completada.
     */
    public function complete(Reminder $reminder, Subtask $subtask): RedirectResponse
    {
        abort_unless($subtask->reminder_id === $reminder->id, 404);
        $this->authorize('update', $subtask);

        $subtask->complete();

        return back();
    }

    /**
     * Desmarca una subtarea completada.
     */
    public function uncomplete(Reminder $reminder, Subtask $subtask): RedirectResponse
    {
        abort_unless($subtask->reminder_id === $reminder->id, 404);
        $this->authorize('update', $subtask);

        $subtask->uncomplete();

        return back();
    }

    /**
     * Actualiza el orden de las subtareas según un array de IDs.
     */
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
     * Reglas de validación compartidas para crear y actualizar subtareas.
     */
    private function subtaskRules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'position' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
