<?php

namespace App\Http\Controllers\Dashboard\Features;

use App\Http\Controllers\Controller;
use App\Http\Requests\Notes\StoreNoteRequest;
use App\Http\Requests\Notes\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * CRUD de notas personales del usuario autenticado.
 *
 * Todas las consultas están scopeadas por `user_id` para garantizar aislamiento
 * entre usuarios dentro del mismo tenant.
 */
class NoteController extends Controller
{
    public function index(): View
    {
        $notes = Note::query()
            ->where('user_id', auth()->id())
            ->select('id', 'user_id', 'title', 'content', 'created_at', 'updated_at')
            ->latest()
            ->get();

        return view('dashboard.features.notes.index', compact('notes'));
    }

    public function create(): View
    {
        return view('dashboard.features.notes.create');
    }

    public function store(StoreNoteRequest $request): RedirectResponse
    {
        Note::create([...$request->validated(), 'user_id' => auth()->id()]);

        return $this->backToIndex('Nota creada correctamente.');
    }

    public function edit(int $id): View
    {
        return view('dashboard.features.notes.edit', ['note' => $this->findOwned($id)]);
    }

    public function update(UpdateNoteRequest $request, int $id): RedirectResponse
    {
        $this->findOwned($id)->update($request->validated());

        return $this->backToIndex('Nota actualizada.');
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->findOwned($id)->delete();

        return $this->backToIndex('Nota eliminada.');
    }

    /**
     * Recupera una nota garantizando que pertenece al usuario autenticado.
     */
    private function findOwned(int $id): Note
    {
        return Note::where('user_id', auth()->id())->findOrFail($id);
    }

    private function backToIndex(string $message): RedirectResponse
    {
        return redirect()->route('dashboard.features.notes.index')->with('success', $message);
    }
}
