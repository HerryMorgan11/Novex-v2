<?php

namespace App\Http\Controllers;

use App\Models\ReminderList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReminderListController extends Controller
{
    /**
     * Lista todas las listas de recordatorios del usuario.
     */
    public function index(): View
    {
        $lists = ReminderList::forUser(auth()->user())
            ->ordered()
            ->get();

        return view('dashboard.features.reminders.lists.index', compact('lists'));
    }

    /**
     * Formulario de creación de lista.
     */
    public function create(): View
    {
        return view('dashboard.features.reminders.lists.create');
    }

    /**
     * Guarda una nueva lista de recordatorios.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate($this->reminderListRules());
        $data['user_id'] = auth()->id();
        $data['position'] = ReminderList::nextPositionForUser(auth()->id());

        $list = ReminderList::create($data);

        // Si se marca como predeterminada, actualizar el resto de listas
        if (! empty($data['is_default'])) {
            $list->makeDefault();
        }

        return redirect()->route('reminders.lists.index')
            ->with('success', 'Lista creada correctamente.');
    }

    /**
     * Formulario de edición de lista.
     */
    public function edit(ReminderList $reminderList): View
    {
        $this->authorize('update', $reminderList);

        return view('dashboard.features.reminders.lists.edit', ['list' => $reminderList]);
    }

    /**
     * Actualiza una lista de recordatorios existente.
     */
    public function update(Request $request, ReminderList $reminderList): RedirectResponse
    {
        $this->authorize('update', $reminderList);

        $reminderList->update($request->validate($this->reminderListRules()));

        if ($request->boolean('is_default')) {
            $reminderList->makeDefault();
        }

        return redirect()->route('reminders.lists.index')
            ->with('success', 'Lista actualizada correctamente.');
    }

    /**
     * Elimina una lista (los recordatorios se conservan sin lista asignada).
     */
    public function destroy(ReminderList $reminderList): RedirectResponse
    {
        $this->authorize('delete', $reminderList);

        // Los recordatorios quedan con reminder_list_id = null (nullOnDelete en migración)
        $reminderList->delete();

        return redirect()->route('reminders.lists.index')
            ->with('success', 'Lista eliminada. Sus recordatorios fueron conservados sin lista.');
    }

    /**
     * Actualiza el orden de las listas según un array de IDs.
     */
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

    /**
     * Reglas de validación compartidas para crear y actualizar listas.
     */
    private function reminderListRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'icon' => ['nullable', 'string', 'max:50'],
            'is_default' => ['boolean'],
            'position' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
